# Installer for standalone shopware plugins

```
composer global require pure/installer

pure-installer new plugin -p PurePluginName
```


### Options
| Option            | short | description                                                               |
|-------------------|-------|---------------------------------------------------------------------------|
| --pluginName      | -p    | The name of the plugin with a namespace prefix i.e. `PureFancyPlugin`     |
| --shopwareVersion | -s    | Shopware version. Default is set to the latest release of shopware        |
| --force           | -f    | Override files. Be careful when using!                                    |
| --workingDir      | ----  | Optional path for creating the plugin                                     |
| --git             | ----  | Initialize a git repo and first commit. Only a remote url have to be set. |
| --branch          | ----  | Init branch name for git. Default is `main`                               |
| --branch          | ----  | Init branch name for git. Default is `main`                               |
