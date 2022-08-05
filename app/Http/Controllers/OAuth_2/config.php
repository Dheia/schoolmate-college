<?php
return array(
        'authorizationRequestUrl' => 'https://appcenter.intuit.com/connect/oauth2', //Example https://appcenter.intuit.com/connect/oauth2',
        'tokenEndPointUrl' => 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer', //Example https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer',
        'client_id' => 'Q0OSScHUtIGNhCKQCSxqvUNjBszWjLapScxgRg7ekJ5GFI9kGt', //Example 'Q0wDe6WVZMzyu1SnNPAdaAgeOAWNidnVRHWYEUyvXVbmZDRUfQ',
        'client_secret' => 't0yAx9QMYSTtISu4aVpIcXMIZluwBc2J9gh6Z4ef', //Example 'R9IttrvneexLcUZbj3bqpmtsu5uD9p7UxNMorpGd',
        'oauth_scope' => 'com.intuit.quickbooks.accounting', //Example 'com.intuit.quickbooks.accounting',
        'openID_scope' => 'admin@tigernethost', //Example 'openid profile email',
        'oauth_redirect_uri' => 'http://localhost', //Example https://d1eec721.ngrok.io/OAuth_2/OAuth2PHPExample.php',
        // 'openID_redirect_uri' => require('OAuth_2/OAuthOpenIDExample.php'),//Example 'https://d1eec721.ngrok.io/OAuth_2/OAuthOpenIDExample.php',
        'mainPage' => 'http://127.0.0.1:8000/admin/api/quickbooks', //Example https://d1eec721.ngrok.io/OAuth_2/index.php',
        'refreshTokenPage' => require('RefreshToken.php'), //Example https://d1eec721.ngrok.io/OAuth_2/RefreshToken.php'
      );
 ?>
