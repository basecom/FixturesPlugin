# Available commands

The FixturePlugin provides a few [symfony commands](https://symfony.com/doc/current/console.html) that you can execute.

## Running fixtures
### Run all fixtures
```shell:no-line-numbers
bin/console fixture:load
```

Possible arguments:
| Argument | Description                                                | Default Value |
| -------- | ---------------------------------------------------------- | ------------- |
| --dry    | Display all fixtures that would run without executing them | false         |
| --vendor | Execute also all fixtures found in the vendor directory    | false         |

### Run a specific fixture
```shell:no-line-numbers
bin/console fixture:load:single {FixtureName}
```

Possible arguments:
| Argument            | Description                                                | Default Value |
| ------------------- | ---------------------------------------------------------- | ------------- |
| --dry               | Display all fixtures that would run without executing them | false         |
| --vendor            | Execute also all fixtures found in the vendor directory    | false         |
| --with-dependencies | Also execute all dependencies recursively                  | false         |
| -w                  | Alias for `--with-dependencies`                            | false         |

### Run a fixture group
```shell:no-line-numbers
bin/console fixture:load:group {GroupName}
```

Possible arguments:
| Argument | Description                                                | Default Value |
| -------- | ---------------------------------------------------------- | ------------- |
| --dry    | Display all fixtures that would run without executing them | false         |
| --vendor | Execute also all fixtures found in the vendor directory    | false         |

## Helper commands
### Get random UUID
```shell:no-line-numbers
bin/console fixture:uuid
```

This command just returns a valid, random UUID.