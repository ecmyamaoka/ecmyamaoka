<?php
use Aws\PhpHash;

$this->assign('title', 'タグマネージャー(R)');

?>

<style>
      @import url(https://fonts.googleapis.com/css?family=Open+Sans:400,600,300);
  body {
    font-family: 'Open Sans', sans-serif;
    background-color: #FFFAF6;
  }
  /*Basic Phone styling*/
  
  .phone {
    border: 40px solid #ddd;
    border-width: 55px 7px;
    border-radius: 40px;
    margin: 20px 50px auto;
    overflow: hidden;
    transition: all 0.5s ease;
  }
  
  .phone iframe {
    border: 0;
    width: 100%;
    height: 100%;
  }
  /*Different Perspectives*/
  
  .phone.view_1 {
    -webkit-transform: rotateX(0deg) rotateY(0deg) rotateZ(0deg);
            transform: rotateX(0deg) rotateY(0deg) rotateZ(0deg);
    box-shadow: 0px 3px 0 #BBB, 0px 4px 0 #BBB, 0px 5px 0 #BBB, 0px 7px 0 #BBB, 0px 10px 20px #666;
  }
  /*Controls*/
  
  #controls {
    position: absolute;
    top: 20px;
    left: 20px;
    font-size: 0.9em;
    color: #333;
  }
  
  #controls div {
    margin: 10px;
  }
  
  #controls div label {
    width: 120px;
    display: block;
    float: left;
  }
  
  #views {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 200px;
  }
  
  #views button {
    width: 198px;
    border: 1px solid #bbb;
    background-color: #fff;
    height: 40px;
    margin: 10px 0;
    color: #666;
    transition: all 0.2s;
  }
  
  #views button:hover {
    color: #444;
    background-color: #eee;
  }
  
  @media (max-width:900px) {
    #wrapper {
      -webkit-transform: scale(0.8, 0.8);
              transform: scale(0.8, 0.8);
    }
  }
  
  @media (max-width:700px) {
    #wrapper {
      -webkit-transform: scale(0.6, 0.6);
              transform: scale(0.6, 0.6);
    }
  }
  
  @media (max-width:500px) {
    #wrapper {
      -webkit-transform: scale(0.4, 0.4);
              transform: scale(0.4, 0.4);
    }
  }
  </style>
<script>
  window.console = window.console || function(t) {};
</script>
<script>
  if (document.location.search.match(/type=embed/gi)) {
    window.parent.postMessage("resize", "*");
  }
</script>
</head>
<body translate="no">

<div class="row margin20">
<div class="col-lg-8 col-md-8 col-xs-12">

</div>
  <div class="col-lg-4 col-md-4 col-xs-4">
  <input type="text" class="form-control" id="iframeURL" placeholder="https://www.rakuten.co.jp/namara44/?force-site=ipn" value="https://www.rakuten.co.jp/namara44/?force-site=ipn" />
  <div id="wrapper">
  <div class="phone view_1" id="phone_1">
  <iframe src="https://www.rakuten.co.jp/namara44/?force-site=ipn" id="frame_1"  onload="alert('2番目に実行されます')"></iframe>
  </div>
  </div>
  </div>
</div>

<!-- <div id="controls" class="margin20">
<div> -->
<!-- <div id="linkBack" style="position:absolute;right:0px;bottom:0px;background-color:#333;margin:0;width:60px;padding:5px"><a href="http://www.f-rilling.com/projects/" target="_blank" style="font-size:14px;text-decoration:none;color:#fff;padding:0 0 0 5px;font-family:sans-serif">My Site</a></div> -->
<!-- <script src="https://static.codepen.io/assets/common/stopExecutionOnTimeout-de7e2ef6bfefd24b79a3f68b414b87b8db5b08439cac3f1012092b2290c719cd.js"></script> -->
<script id="rendered-js">



/*View*/
function updateView(view) {
  if (view) {
    phone.className = "phone view_" + view;
  }
}



function loadFnc(elm){
 
  var __originalNavigator = navigator;
  navigator = new Object();
  navigator.__defineGetter__('userAgent', function () {
      return 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0 Mobile/15E148 Safari/604.1';
  });
}

/*Controls*/
function updateIframe() {
  iframe.src = document.getElementById("iframeURL").value;

  phone.style.width = "350px";
  phone.style.height = "650px";



  /*Idea by /u/aerosole*/
  // document.getElementById("wrapper").style.perspective =
  // document.getElementById("iframePerspective").checked ? "1000px" : "none";

}

    /*Only needed for the controls*/
var phone = document.getElementById("phone_1"),
iframe = document.getElementById("frame_1");
updateIframe();

/*Events*/
// document.getElementById("controls").addEventListener("change", function () {
//   updateIframe();
// });

// document.getElementById("views").addEventListener("click", function (evt) {
//   updateView(evt.target.value);
// });
      //# sourceURL=pen.js
    </script>