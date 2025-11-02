# Naming convention

Small library to detect and convert between naming conventions.

| Convention                               | Examples               |
|:-----------------------------------------|:-----------------------|
| `NamingConvention::CAMEL_CASE`           | `camel`, `myVarName`   |
| `NamingConvention::SNAKE_CASE`           | `snake`, `my_var_name` |
| `NamingConvention::SCREAMING_SNAKE_CASE` | `SNAKE`, `MY_VAR_NAME` |
| `NamingConvention::PASCAL_CASE`          | `Pascal`, `MyVarName`  |
| `NamingConvention::KEBAB_CASE`           | `kebab` `my-var-name`  |

## Usage

Via composer: `composer require intellex/naming-convention`

```php
use Intellex\NamingConvention\NamingConvention

# Validate
NamingConvention::CAMEL_CASE->validate('myVarName');  // true
NamingConvention::SNAKE_CASE->validate('Snake_Case'); // false

# Detect the convention
$varName =  NamingConvention::determineApplicableConvention('my_var_name');
$varName->convention // NamingConvention::SNAKE_CASE

# Convert
$newName = $varName->convertTo(NamingConvention::PASCAL_CASE);
$newName->name       // "MyVarName"
```

## Related projects

This library is developed for the automated
[data parsing library](https://github.com/IntellexApps/data-parser).

## License

Licenced under [MIT Licence](LICENSE).

## Credits

Written by the [Intellex team](https://intellex.rs/en).
