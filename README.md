# Laravel model validation

## Requirements

- PHP 7.1+
- The Laravel framework 7.0+

## Installation

You may use composer to install the laravel-model-validation plugin into your Laravel project;

```shell script
composer require engency/laravel-model-validation
```

Use the Validatable trait on the models you would like to perform validation on.
 
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Engency\ModelValidation\Validatable;

Class User extends Model
{
   use Validatable;
}
```

Create a new directory in the app directory called 'ModelValidators'. This directory will contain all rules which apply for the concerning models. For each model using the Validatable trait, create a file called {name of the model}ModelValidator.php. A ModelValidator should look like following;

```php
namespace App\ModelValidators;

use Engency\ModelValidation\ModelValidator;

Class UserModelValidator extends ModelValidator
{
    /**
     * @return array
     */
    public function rules() : array {
        return [
            'name' => 'required|string'
        ];
    }   
}
``` 

Validating and creating a new user is now very easy;

```php
$user = User::validateAndCreateNew(['name' => 'John']);
```

Updating an existing users works almost the same;

```php
$user->validateAndUpdate(['name' => 'John']);
```

You could add additional sets of rules to each model;

```php
namespace App\ModelValidators;

use Engency\ModelValidation\ModelValidator;

Class UserModelValidator extends ModelValidator
{
    /**
     * @return array
     */
    public function rules() : array {
        return [
            'name' => 'required|string'
        ];
    }

    public function otherRules() : array {
        return [
            'name' => 'required|string|min:5',
            'age' => 'required|integer|min:22'
        ];
    }

}
```
```php
$user = User::validateAndCreateNew(['name' => 'John', 'age' => 25], 'other');
$user->validateAndUpdate(['name' => 'John', 'age' => 25], 'other');
```

For a list of all rules, please visit the Laravel Validation documentation;
https://laravel.com/docs/validation

## Contributors

- Frank Kuipers ([GitHub](https://github.com/frankkuipers))

## License

This plugin is licenced under the [MIT license](https://opensource.org/licenses/MIT).
