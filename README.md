# Command Line Interface for shopware plugins


## Installing
```
composer require --dev pureware/pureware-cli
```

## Run Command
```
vendor/bin/pure help
```

## Configuring A Shell Alias
```
alias pure='vendor/bin/pure';
```

## Make command
Run `pure make:` to see all commands.
Run `pure make:entity --help` to see all options for a given command.

### Options for all commands
`--workinDir Custom/Path/To/Directory` works for every command and overrides the default path where a file is added.
`--force` or `-f` overrides already existing files.

### Entity Generation
`pure make:entity CustomData` creates a new entity named CustomData.
`pure make:entity CustomData --translation` creates a new entity named CustomData with a CustomDataTranslationEntity.
`pure make:entity CustomData --translation --prefix pure --workingDir Data/Entities` Example with translations, table prefix and a custom directory inside the src directory of the plugin.
### Options
| option            | short | value | description                                                         |
|-------------------|-------|-------|---------------------------------------------------------------------|
| --translation     | -t    | -     | Make a TranslationEntity in a subdirectory (Aggregate/Translations) |
| --migration       | -m    | -     | Make a Migration File in Migration Directory                        |
| --hydrator        | -     | -     | Make a EntityHydrator class                                         |
| --many2many       | -     | -     | Make a EntityMappingClass for a man2many association                |
| --prefix[=PREFIX] | -     | -     | The sql table prefix for given Entity                               |
|                   |       |       |                                                                     |

### More Commands
Docs for all commands coming soon!


