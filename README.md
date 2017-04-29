# JS Post Load

Drupal 8 module. Loads all Javascript located at page bottom after the onLoad event has been fired. This truly deferres the load of JS files.

## Installation ##

Add GitHub's repository to composer.json

```json
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:asilgag/js-post-load.git"
        }
    ],
```

Execute composer require:

```
composer require asilgag/js_post_load
```
