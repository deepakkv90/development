    <script type="text/javascript" src="includes/javascript/image_manager/assets/dialog.js"></script>
    <script type="text/javascript" src="includes/javascript/image_manager/IMEStandalone.js"></script>
    <script type="text/javascript">
    //<![CDATA[

        //Create a new Imanager Manager, needs the directory where the manager is
        //and which language translation to use.
        var manager = new ImageManager('includes/javascript/image_manager/','en');

        //Image Manager wrapper. Simply calls the ImageManager
        ImageSelector =
        {
            //This is called when the user has selected a file
            //and clicked OK, see popManager in IMEStandalone to
            //see the parameters returned.
            update : function(params)
            {
                if(this.field && this.field.value != null)
                {
                    this.field.value = params.f_file; //params.f_url
                }
            },
            //open the Image Manager, updates the textfield
            //value when user has selected a file.
            select: function(textfieldID)
            {
                this.field = document.getElementById(textfieldID);
                manager.popManager(this);
            }
        };

    //]]>
    </script>