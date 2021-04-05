# grumphp-stylelint-task

## Installation

[Stylelint](https://stylelint.io/) is a static analysis tool for styles. A mighty, modern linter that helps you avoid errors and enforce conventions in your styles.

## npm

1\. Use [npm](https://docs.npmjs.com/about-npm/) to install stylelint and its [`standard configuration`](https://github.com/stylelint/stylelint-config-standard):

```shell
npm install --save-dev stylelint stylelint-config-standard
```

2\. Create a `.stylelintrc.json` configuration file in the root of your project:

```json
{
  "extends": "stylelint-config-standard"
}
```

3\. Run stylelint on, for example, all the CSS files in your project:

```shell
npx stylelint "**/*.css"
```

## Config
It lives under the `stylelint` namespace and has the following configurable parameters:

```yaml
# grumphp.yml
grumphp:
    tasks:
        stylelint:
            triggered_by: [css, scss, less]
            whitelist_patterns:
                - /^resources\/css\/(.*)/
            config: .stylelintrc.json
            max-warnings: 5
            quiet: false
      
parameters:
    extensions:
        - Adrifkat\GrumPHPStylelint\Extension

```

**triggered_by**

*Default: [less, sass, scss, css]*

This is a list of extensions which will trigger the Stylelint task.


**whitelist_patterns**

*Default: []*

This is a list of regex patterns that will filter files to validate. With this option you can specify the folders containing style files and thus skip folders like the /vendor/ directory. This option is used in conjunction with the parameter `triggered_by`.
For example: to whitelist files in `resources/css/` (Laravel's CSS directory) you can use:
```yml
whitelist_patterns:
  - /^resources\/css\/(.*)/
```

**config**

*Default: null*

Path to a JSON, YAML, or JS file that contains your configuration object. Not necessary if using a standard stylelintrc name, eg. .stylelintrc.json

**max-warnings**

*Default: null*

Set a limit to the number of warnings accepted. ([stylelint.io](https://stylelint.io/user-guide/usage/cli#--max-warnings---mw)).

**quiet**

*Default: null*

Only register violations for rules with an "error"-level severity (ignore "warning"-level). ([stylelint.io](https://stylelint.io/user-guide/usage/cli#--quiet--q))