<script type="text/javascript">
					(function() {
					  // store the slider in a local variable
					  var $window = $(window),
						  flexslider;
					 
					  // tiny helper function to add breakpoints
					  function getGridSize() {
						return (window.innerWidth < 320) ? 1 :
							   (window.innerWidth < 600) ? 2 :
							   (window.innerWidth < 800) ? 3 :
							   (window.innerWidth < 900) ? 4 : 5;
					  }
					  $window.load(function() {
						$('#content .featured_carousel').flexslider({
						  animation: "slide",
						  animationLoop: false,
						  slideshow: false,
						  itemWidth: 210,
						  minItems: getGridSize(), // use function to pull in initial value
						  maxItems: getGridSize() // use function to pull in initial value
						});
					  });
					}());
				</script>
