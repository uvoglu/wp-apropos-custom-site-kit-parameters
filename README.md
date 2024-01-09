# Custom Site Kit Parameters

A WordPress plugin that loggs additional parameters via Google Analytics. 

The plugin extends the [Site Kit by Google](https://wordpress.org/plugins/google-site-kit/) plugin by adding additional parameters to the `gtag('config', ...)` call using the `googlesitekit_gtag_opt` filter. For those parameters, custom event-based dimensions can then be created in Google Analytics.

The [Site Kit by Google](https://wordpress.org/plugins/google-site-kit/) plugin needs to be installed and configured for this plugin to work.

## Which Parameters are Sent?

The plugin will only send additional parameters on single post pages (i.e., when `is_singular( 'post' )` is true).

On such a page, the `gtag('config', ...)` call might look something like this:

```js
gtag("config", "GT-XYZ", {"apropos_issue_title":"N\u00b0 183 \u2013 November 2023","apropos_issue_slug":"183_en","apropos_post_title":"Is there still a role for security sector reform in the Sahel?","apropos_post_slug":"is-there-still-a-role-for-security-sector-reform-in-the-sahel","apropos_post_language":"de"});
```

The following custom parameters are added:

### `apropos_issue_title`

This is the readable title of an issue. If translations are available for the issue and they are linked correctly, this will correspond to the English title of the issue. If no translations can be found, it will correspond to the title of the issue in the current language.

Using the English value if possible makes sure that views can be summed up for all languages. If needed, the `apropos_post_language` can then be used to split up the data again into individual languages.

### `apropos_issue_slug`

This is the slug of an issue, i.e., the name of the issue in an URL-friendly form. `183_en` is the slug of the full URL `https://koff.swisspeace.ch/apropos/issue/183_en/`. If translations are available for the issue and they are linked correctly, this will correspond to the English slug of the issue. If no translations can be found, it will correspond to the slug of the issue in the current language.

Using the English value if possible makes sure that views can be summed up for all languages. If needed, the `apropos_post_language` can then be used to split up the data again into individual languages.

### `apropos_post_title`

This is the readable title of a post. If translations are available for the post and they are linked correctly, this will correspond to the English title of the post. If no translations can be found, it will correspond to the title of the post in the current language.

Using the English value if possible makes sure that views can be summed up for all languages. If needed, the `apropos_post_language` can then be used to split up the data again into individual languages.

### `apropos_post_slug`

This is the slug of an post, i.e., the name of the post in an URL-friendly form. `ist-eine-reform-des-sicherheitssektors-in-der-sahelzone-noch-sinnvoll` is the slug of the full URL `https://koff.swisspeace.ch/apropos/2836/ist-eine-reform-des-sicherheitssektors-in-der-sahelzone-noch-sinnvoll/`. If translations are available for the post and they are linked correctly, this will correspond to the English slug of the post. If no translations can be found, it will correspond to the slug of the post in the current language.

### `apropos_post_language`

This is the two-letter language code in which the post is viewed (e.g., `en`, `de`, `fr`). Polylang is needed for this to work.


## Using the Custom Parameters in Google Analytics

Sending the parameters in the `gtag('config', ...)` makes sure that those parameters are then logged in Google Analytics, e.g., for the `page_view` event. To use the values in Google Analytics, create event-based custom dimensions for the corresponding parameters. More info can be found [here](https://support.google.com/analytics/answer/14239696?sjid=3989273633687516621-EU#zippy=%2Canalyze-the-custom-dimension-in-a-report%2Canalyze-the-custom-dimension-in-an-exploration%2Cbuild-an-audience-using-the-dimension).

Note that sending custom parameters via `gtag('config', ...)` is currently not really documented for Google Analytics 4, but this is how the official [Site Kit by Google](https://wordpress.org/plugins/google-site-kit/) plugin is working as well for its custom dimensions (e.g., `googlesitekit_post_categories`).
