/* Was used in our first crack at the FB SDK. Can probably be removed soon*/
// window.fbAsyncInit = function() {
//         FB.init({
//           appId      : '1403610066585894',
//           xfbml      : true,
//           version    : 'v2.0'
//         });
//       };

//       (function(d, s, id){
//          var js, fjs = d.getElementsByTagName(s)[0];
//          if (d.getElementById(id)) {return;}
//          js = d.createElement(s); js.id = id;
//          js.src = "//connect.facebook.net/en_US/sdk.js";
//          fjs.parentNode.insertBefore(js, fjs);
//        }(document, 'script', 'facebook-jssdk'));

// Load the SDK asynchronously
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

// This is called with the results from from FB.getLoginStatus().
function statusChangeCallback(response) {
  // console.log('statusChangeCallback');
  // console.log(response);
  // The response object is returned with a status field that lets the
  // app know the current login status of the person.
  // Full docs on the response object can be found in the documentation
  // for FB.getLoginStatus().

  // Store the 'login' text so that we can use it when the avatar is hidden. Couldn't straight inject it as we need the translation from WPML
  FB.loginText =  jQuery('.avatar').html();

  if (response.status === 'connected') {
    // Logged into your app and Facebook. Handled on page load rather than a user clicking login/logout
    fbExchangeToken();
    toggleAvatar(true);

  } else if (response.status === 'not_authorized') {
    // The person is logged into Facebook, but not your app.
    // document.getElementById('status').innerHTML = 'Please log ' +
    //   'into this app.';

    // FB.logout();

  } else {
    // The person is not logged into Facebook, so we're not sure if
    // they are logged into this app or not.
    // document.getElementById('status').innerHTML = 'Please log ' +
    //   'into Facebook.';

    // FB.logout();

  }
}

// This function is called when someone finishes with the Login
// Button.  See the onlogin handler attached to it in the sample
// code below.
function checkLoginState() {
  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });
}

window.fbAsyncInit = function() {
  FB.init({
    appId      : facebookAppId,
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.2' // use version 2.2
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

};

// Here we run a very simple test of the Graph API after login is
// successful.  See statusChangeCallback() for when this call is made.
function testAPI() {
  console.log('Welcome!  Fetching your information.... ');
  FB.api('/me', function(response) {
    // console.log('Successful login for: ' + response.name);
    document.getElementById('status').innerHTML =
      'Thanks for logging in, ' + response.name + '!';
  });
}

// Toggle the avatar
function toggleAvatar(show){
  if(show){

    FB.api('/me', function(response){

      // Get the user's picture and set it to the .avatar
      FB.api('/' + response.id +'/picture', function(response){
        // Got the user's picture
        jQuery('.avatar').html('<img src=' + response.data.url + '>');

        // Add the fb-logged-in class to the body
        jQuery('body').addClass('fb-logged-in');
      })
    });
  }
  else{
    jQuery('.avatar').html('Login');

    // Remove the fb-logged-in class from the body
    jQuery('body').removeClass('fb-logged-in');
  }
}
// Calls to PHP AJAX handler to exchange to token for a long-lived fella
function fbExchangeToken(){

  var data = {
    action: 'fb_exchange_token_ajax',
    token: FB.getAccessToken()
  };

  // the_ajax_script.ajaxurl is a variable that will contain the url to the ajax processing file
  jQuery.post(the_ajax_script.ajaxurl, data, function(response) {
    // console.log(response);
  });
}

function fbToggleLogin(){
  var status;

  // Get the current state of login
  FB.getLoginStatus(function(response) {
    status = response.status;
  });

  if(status === 'connected'){
    // Already logged in.  Log out
    fbLogout();
    toggleAvatar(false);
  }
  else if (status === 'not_authorized') {
    // User is logged in to FB but not our app. Log them out before logging them in
    fbLogout();

    fbLogin();
  }
  else{
    // Not logged in.

    fbLogin();
  }
}

function fbLogout(){

  // Destroy the PHP $_SESSION on the server
  var data = {
    action: 'fb_kill_token'
  };

  // the_ajax_script.ajaxurl is a variable that will contain the url to the ajax processing file
  jQuery.post(the_ajax_script.ajaxurl, data, function(response) {
    // console.log(response);
  });

  // Invalidate the token
  FB.api('/me/permissions', 'DELETE', function(response){

    console.log('Logged out from Facebook');
    // Destroy the JS session
    FB.logout();
  });

  
}

function fbLogin(){
  FB.login(function(response) {
    // handle the response
    console.log(response);
    // Show the user's avatar
    toggleAvatar(true);

    // Initiate an AJAX call to swap the tokens on the server and store the long-lived token in a PHP $_SESSION
    fbExchangeToken();
  }, {
    scope: 'publish_actions',
    return_scopes: true
  });
}


(function($){
  $(document).ready(function(){

    // Bind the login functionality to the button
    $('.avatar').on('click', function(){
      fbToggleLogin();
    });

  });
})(jQuery);


var fbPost = function( token, media, body ){
  (function($){
    var data = {
      action: 'ajax_post_to_facebook',
      token: token,
      media: media,
      body: body
    };
    // the_ajax_script.ajaxurl is a variable that will contain the url to the ajax processing file
    $.post(the_ajax_script.ajaxurl, data, function(response) {
      console.log(response);
    });
    return false;
  })(jQuery);
};  

