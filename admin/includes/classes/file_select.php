<?php
/**
 * DirSelect, list images, directories, and thumbnails.
 * @author $Author: Wei Zhuo $
 * @version $Id: DirSelect.php 27 2004-04-01 08:31:57Z Wei Zhuo $
 * @package DirSelect
 */

// require_once('class_files.php');

class Files
{

   /**
     * Escape the filenames, any non-word characters will be
     * replaced by an underscore.
     * @param string $filename the orginal filename
     * @return string the escaped safe filename
     */
    function escape($filename)
    {
        Return preg_replace('/[^\w\._]/', '_', $filename);
    }


    /**
     * Append a / to the path if required.
     * @param string $path the path
     * @return string path with trailing /
     */
    function fixPath($path)
    {
        //append a slash to the path if it doesn't exists.
        if(!(substr($path,-1) == '/'))
            $path .= '/';
        Return $path;
    }

    /**
     * Concat two paths together. Basically $pathA+$pathB
     * @param string $pathA path one
     * @param string $pathB path two
     * @return string a trailing slash combinded path.
     */
    function makePath($pathA, $pathB)
    {
        $pathA = Files::fixPath($pathA);
        if(substr($pathB,0,1)=='/')
            $pathB = substr($pathB,1);
        Return Files::fixPath($pathA.$pathB);
    }

    /**
     * Similar to makePath, but the second parameter
     * is not only a path, it may contain say a file ending.
     * @param string $pathA the leading path
     * @param string $pathB the ending path with file
     * @return string combined file path.
     */
    function makeFile($pathA, $pathB)
    {
        $pathA = Files::fixPath($pathA);
        if(substr($pathB,0,1)=='/')
            $pathB = substr($pathB,1);

        Return $pathA.$pathB;
    }


    /**
     * Format the file size, limits to Mb.
     * @param int $size the raw filesize
     * @return string formated file size.
     */
    function formatSize($size)
    {
        if($size < 1024)
            return $size.' bytes';
        else if($size >= 1024 && $size < 1024*1024)
            return sprintf('%01.2f',$size/1024.0).' Kb';
        else
            return sprintf('%01.2f',$size/(1024.0*1024)).' Mb';
    }
}
//end file class


/**
 * DirSelect Class.
 * @author $Author: Wei Zhuo $
 * @version $Id: DirSelect.php 27 2004-04-01 08:31:57Z Wei Zhuo $
 */
class DirSelect
{
    /**
     * Configuration array.
     */
    var $config;

    /**
     * Array of directory information.
     */
    var $dirs;

    /**
     * Constructor. Create a new Image Manager instance.
     * @param array $config configuration array, see config.inc.php
     */
    function DirSelect($config)
    {
        $this->config = $config;
    }

    /**
     * Get the base directory.
     * @return string base dir, see config.inc.php
     */
    function getBaseDir()
    {
        Return $this->config['base_dir'];
    }

    /**
     * Get the base URL.
     * @return string base url, see config.inc.php
     */
    function getBaseURL()
    {
        Return $this->config['base_url'];
    }

    function isValidBase()
    {
        return is_dir($this->getBaseDir());
    }


    /**
     * Get the sub directories in the base dir.
     * Each array element contain
     * the relative path (relative to the base dir) as key and the
     * full path as value.
     * @return array of sub directries
     * <code>array('path name' => 'full directory path', ...)</code>
     */
    function getDirs()
    {
        if(is_null($this->dirs))
        {
            $dirs = $this->_dirs($this->getBaseDir(),'/');
            ksort($dirs);
            $this->dirs = $dirs;
        }
        return $this->dirs;
    }

    /**
     * Recursively travese the directories to get a list
     * of accessable directories.
     * @param string $base the full path to the current directory
     * @param string $path the relative path name
     * @return array of accessiable sub-directories
     * <code>array('path name' => 'full directory path', ...)</code>
     */
    function _dirs($base, $path)
    {
        $base = Files::fixPath($base);
        $dirs = array();

        if($this->isValidBase() == false)
            return $dirs;

        $d = @dir($base);

        while (false !== ($entry = $d->read()))
        {
            //If it is a directory, and it doesn't start with
            // a dot, and if is it not the thumbnail directory
            if(is_dir($base.$entry)
                && substr($entry,0,1) != '.')
            {
                $relative = Files::fixPath($path.$entry);
                $fullpath = Files::fixPath($base.$entry);
                $dirs[$relative] = $fullpath;
                $dirs = array_merge($dirs, $this->_dirs($fullpath, $relative));
            }
        }
        $d->close();

        Return $dirs;
    }

    /**
     * Get all the files and directories of a relative path.
     * @param string $path relative path to be base path.
     * @return array of file and path information.
     * <code>array(0=>array('relative'=>'fullpath',...), 1=>array('filename'=>fileinfo array(),...)</code>
     * fileinfo array: <code>array('url'=>'full url',
     *                       'relative'=>'relative to base',
     *                        'fullpath'=>'full file path',
     *                        'image'=>imageInfo array() false if not image,
     *                        'stat' => filestat)</code>
     */
    function getFiles($path)
    {
        $files = array();
        $dirs = array();

        if($this->isValidBase() == false)
            return array($files,$dirs);

        $path = Files::fixPath($path);
        $base = Files::fixPath($this->getBaseDir());
        $fullpath = Files::makePath($base,$path);


        $d = @dir($fullpath);

        while (false !== ($entry = $d->read()))
        {
            //not a dot file or directory
            if(substr($entry,0,1) != '.')
            {
                if(is_dir($fullpath.$entry))
                {
                    $relative = Files::fixPath($path.$entry);
                    $full = Files::fixPath($fullpath.$entry);
                    $count = $this->countFiles($full);
                    $dirs[$relative] = array('fullpath'=>$full,'entry'=>$entry,'count'=>$count);
                }
                else if(is_file($fullpath.$entry))
                {
                    $img = $this->getImageInfo($fullpath.$entry);

                    if(!(!is_array($img)&&$this->config['validate_images']))
                    {
                        $file['url'] = Files::makePath($this->config['base_url'],$path).$entry;
                        $file['relative'] = $path.$entry;
                        $file['fullpath'] = $fullpath.$entry;
                        $file['image'] = $img;
                        $file['stat'] = stat($fullpath.$entry);
                        $files[$entry] = $file;
                    }
                }
            }
        }
        $d->close();
        ksort($dirs);
        ksort($files);

        Return array($dirs, $files);
    }

    /**
     * Count the number of files and directories in a given folder
     * minus the thumbnail folders and thumbnails.
     */
    function countFiles($path)
    {
        $total = 0;

        if(is_dir($path))
        {
            $d = @dir($path);

            while (false !== ($entry = $d->read()))
            {
                //echo $entry."<br>";
                if(substr($entry,0,1) != '.')
                {
                    $total++;
                }
            }
            $d->close();
        }
        return $total;
    }

    /**
     * Get image size information.
     * @param string $file the image file
     * @return array of getImageSize information,
     *  false if the file is not an image.
     */
    function getImageInfo($file)
    {
        Return @getImageSize($file);
    }



    /**
     * Check if the given path is part of the subdirectories
     * under the base_dir.
     * @param string $path the relative path to be checked
     * @return boolean true if the path exists, false otherwise
     */
    function validRelativePath($path)
    {
        $dirs = $this->getDirs();
        if($path == '/')
            Return true;
        //check the path given in the url against the
        //list of paths in the system.
        for($i = 0; $i < count($dirs); $i++)
        {
            $key = key($dirs);
            //we found the path
            if($key == $path)
                Return true;

            next($dirs);
        }
        Return false;
    }


     /**
     * Get the URL of the relative file.
     * basically appends the relative file to the
     * base_url given in config.inc.php
     * @param string $relative a file the relative to the base_dir
     * @return string the URL of the relative file.
     */
    function getFileURL($relative)
    {
        Return Files::makeFile($this->getBaseURL(),$relative);
    }

    /**
     * Get the fullpath to a relative file.
     * @param string $relative the relative file.
     * @return string the full path, .ie. the base_dir + relative.
     */
    function getFullPath($relative)
    {
        Return Files::makeFile($this->getBaseDir(),$relative);;
    }

    /**
     * Get the default thumbnail.
     * @return string default thumbnail, empty string if
     * the thumbnail doesn't exist.
     */
    function getDefaultThumb()
    {
        if(is_file($this->config['default_thumbnail']))
            Return $this->config['default_thumbnail'];
        else
            Return '';
    }


    /**
     * Get the thumbnail url to be displayed.
     * If the thumbnail exists, and it is up-to-date
     * the thumbnail url will be returns. If the
     * file is not an image, a default image will be returned.
     * If it is an image file, and no thumbnail exists or
     * the thumbnail is out-of-date (i.e. the thumbnail
     * modified time is less than the original file)
     * then a thumbs.php?img=filename.jpg is returned.
     * The thumbs.php url will generate a new thumbnail
     * on the fly. If the image is less than the dimensions
     * of the thumbnails, the image will be display instead.
     * @param string $relative the relative image file.
     * @return string the url of the thumbnail, be it
     * actually thumbnail or a script to generate the
     * thumbnail on the fly.
     */
    function getThumbnail($relative)
    {
        $fullpath = Files::makeFile($this->getBaseDir(),$relative);

        //not a file???
        if(!is_file($fullpath))
            Return $this->getDefaultThumb();

        $imgInfo = @getImageSize($fullpath);

        //not an image
        if(!is_array($imgInfo))
            Return $this->getDefaultThumb();

        //the original image is smaller than thumbnails,
        //so just return the url to the original image.
        if ($imgInfo[0] <= $this->config['thumbnail_width']
         && $imgInfo[1] <= $this->config['thumbnail_height'])
            Return $this->getFileURL($relative);

        $thumbnail = $this->getThumbName($fullpath);

        //check for thumbnails, if exists and
        // it is up-to-date, return the thumbnail url
        if(is_file($thumbnail))
        {
            if(filemtime($thumbnail) >= filemtime($fullpath))
                Return $this->getThumbURL($relative);
        }

        //well, no thumbnail was found, so ask the thumbs.php
        //to generate the thumbnail on the fly.
        Return tep_href_link('img_thumbs.php', 'img=' . rawurlencode($relative), 'SSL');
    }



    /**
     * Create new directories.
     * If in safe_mode, nothing happens.
     * @return boolean true if created, false otherwise.
     */
    function processNewDir()
    {
        if($this->config['safe_mode'] == true)
            Return false;

        if(isset($_GET['newDir']) && isset($_GET['dir']))
        {
            $newDir = rawurldecode($_GET['newDir']);
            $dir = rawurldecode($_GET['dir']);
            $path = Files::makePath($this->getBaseDir(),$dir);
            $fullpath = Files::makePath($path, Files::escape($newDir));
            if(is_dir($fullpath))
                Return false;

            Return Files::createFolder($fullpath);
        }
    }

    /**
     * Do some graphic library method checkings
     * @param string $library the graphics library, GD, NetPBM, or IM.
     * @param string $method the method to check.
     * @return boolean true if able, false otherwise.
     */
    function validGraphicMethods($library,$method)
    {
        switch ($library)
        {
            case 'GD':
                return $this->_checkGDLibrary($method);
                break;
            case 'NetPBM':
                return $this->_checkNetPBMLibrary($method);
                break;
            case 'IM':
                return $this->_checkIMLibrary($method);
        }
        return false;
    }

    function _checkIMLibrary($method)
    {
        //ImageMagick goes throught 1 single executable
        if(is_file(Files::fixPath(IMAGE_TRANSFORM_LIB_PATH).'convert'))
            return true;
        else
            return false;
    }

    /**
     * Check the GD library functionality.
     * @param string $library the graphics library, GD, NetPBM, or IM.
     * @return boolean true if able, false otherwise.
     */
    function _checkGDLibrary($method)
    {
        $errors = array();
        switch($method)
        {
            case 'create':
                $errors['createjpeg'] = function_exists('imagecreatefromjpeg');
                $errors['creategif'] = function_exists('imagecreatefromgif');
                $errors['createpng'] = function_exists('imagecreatefrompng');
                break;
            case 'modify':
                $errors['create'] = function_exists('ImageCreateTrueColor') || function_exists('ImageCreate');
                $errors['copy'] = function_exists('ImageCopyResampled') || function_exists('ImageCopyResized');
                break;
            case 'save':
                $errors['savejpeg'] = function_exists('imagejpeg');
                $errors['savegif'] = function_exists('imagegif');
                $errors['savepng'] = function_exists('imagepng');
                break;
        }

        return $errors;
    }
}

?>
