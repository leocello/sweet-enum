# SweetEnum

**SweetEnum** is a package that will make your backed enums sweeter. It allows you to add custom properties and functionality to backed enums in a very sweet way.  

---

## Installation  

To install **SweetEnum** to your PHP project, just run:  
```shell
composer install leocello/sweet-enum
```
---

## Usage  

### SweetEnum basic structure

`SweetEnum` will allow you by using `PHP Attributes` to define into your enums custom properties and functionality for each case without the necessity to manually iterate or `match()` all of them, what makes your enums look very sweet.

Also, it provides some cool methods to allow you to iterate among the cases as if it was a collection to help your whole application's code readability.

To make it happen, it requires the enum to implement the interface `SweetEnumContract` and use the trait `SweetEnum`. Also, for each case, you need to add the attribute `SweetCase` with its properties. This is how a simple `SweetEnum` with no custom properties looks like:

```php
use Leocello\SweetEnum\SweetCase;
use Leocello\SweetEnum\SweetEnum;
use Leocello\SweetEnum\SweetEnumContract;

enum Status: string implements SweetEnumContract
{
    use SweetEnum;
    
    #[SweetCase(
        title: 'Active',
        custom: [
            'color' => 'green',
        ],
    )
    case Active = 'active';

    #[SweetCase(
        title: 'Inactive',
        custom: [
            'color' => 'red',
        ],
    )
    case Inactive = 'inactive';
}
```

This already allows you to use the cool stuff `SweetEnum` provides. Note that all cases have a named argument `title` and a `custom`. Also, you can pass another argument `isOn` that will determine if that options is currently active (what is very useful to maintain functionality for legacy data) and a case class, what will be explained further in this document.

To access any property, including the custom ones, you just use them as a method to your enum case. For example:

```php
echo Status::Active->title(); // Will print: Active
echo Status::Inactive->color(); // Will print: red
```

### Default case

A constant named `DEFAULT` may be defined with the value of one of the cases of the enum. If no default case is defined, then the first case of the enum will be considered default.

Example:

```php
public const DEFAULT = self::Active;
```

### Some case methods

#### `->isOfType(class-string<SweetEnumContract> $type): bool`:

It returns if the case is of enum type given. The parameter is a `string-class` so it is pretty exception safe if a string is passed.

Example:

```php
$status = Status::Active;

...

if ($status->isOfType(Status::class)) {
    // Do something
}

if ($status->isOfType(Color::class)) {
    // Not gonna happen
}
```

#### `->is(SweetEnumContract|SweetEnumContract[] $case): bool`:

It accepts one argument that can be one enum case or an array of enum cases. And it returns a `bool` value being `true` if the case is of the type (or one of types) passed and `false` if not.

If the case passed is not of the same enum, then an exception (`\InvalidArgumentException`) will be thrown.

Example:

```php
$status = Status::Active;

...

if ($status->is(Status::Inactive)) {
    // Not gonna happen
}

if ($status->is(Color::White)) {
    // Also not gonna happen as it already threw an exception :(
}

if ($status->is(Status::Active)) {
    // Do something
}
```

#### `->toArray(array|string $fields = self::FIELDS_SWEET_BASIC)`:

This method returns the properties of the case as an associative array. It receives one argument that determines the return. It can receive one of the defined constants for the format or a custom array of strings where each position is a field to be returned in the array.

The possible defined formats are:

- `::FIELDS_ORIGINAL` to return only the backed enums properties `value` and `name`
- `::FIELDS_SWEET_BASIC` to return only the basic properties `id` and `title`
- `::FIELDS_SWEET_WITH_STATUS` to return only the basic properties `id` and `title` with the addition of `isOn`
- `::FIELDS_SWEET_FULL` to return all values for that case: `value`, `id`, `name`, `title`, `isOn` and all the custom defined properties, computed properties and properties defined in the case classes.

Optionally you can pass an array with all the property names desired, for example: `['title', 'color']`.

Some examples:

```php
Status::Active->toArray(Status::FIELDS_SWEET_FULL);
// Will return:
// [
//     'isOn' => true,
//     'value' => 'active',
//     'id' => 'active',
//     'name' => 'Active',
//     'title' => 'Active',
//     'color' => 'red',
// ]

Status::Active->toArray(['id', 'color']);
// Will return:
// [
//     'id' => 'active',
//     'color' => 'red',
// ]

```

### Static enum method

#### `getCases(bool $onlyActive = true): array`

This method is similar to `\BAckedEnum` method `::cases()` but it uses the case status to return the options. By default only the active cases (`isOn = true`) will be returned, but if the value of argument `onlyActive` is set as `false` then all cases are returned.

#### `getCasesInfo(array|string $fields = SweetEnumContract::FIELDS_SWEET_BASIC, bool $onlyActives = true): array`

This static method returns an array of arrays with all cases information. The information by case is the same result of the method `toArray()`, so it accepts the parameter `$fields` that behaves the same way as it does in `toArray()` where it's determined what info each case will have. Also it accepts the parameter `onlyActive` (by default = `true`), that is used to filter only active cases. For example:

```php
$info = Color::getCasesInfo(Color::FIELDS_SWEET_ORIGINAL);

// The variable $info will be an array where each value will be
// an associative array with the keys `value` and `name`
```

#### `getDefaultCase(): SweetEnumContract`

This method returns the default case of the enum. The default case is defined by the constant `DEFAULT`. And if it's not explicitly defined, the first case will be returned as default.

#### `getRandomCase(): SweetEnumContract`

This method will return one of the cases of the enum randomly.

### Basic enum "properties"

Because PHP doesn't support custom properties for enums, the values are accessed by methods, but we will call them properties as they will return a defined value.

#### `->id()`:

This is an alias for `MyEnum::MyCase->value`.

#### `->name()`:

This is an alias for `MyEnum::MyCase->name`.

#### `->title()`:

This returns the string value passed to argument `title`. By default if not described it uses the enum's name (so `MyEnum::MyCase->title()` would return the same as `MyEnum::MyCase->name`).

#### `->isOn()`:

This returns the boolean value passed to argument `isOn`. By default if not described it returns `true`.

### Custom defined "properties"

The custom properties are defined as an associative array to each case, via argument `custom`. The properties then are accessed by calling a method with the same name as the key used for that value.

For example:

```php
...

#[SweetCase(
    custom: [
        'color' => 'green',
    ],
)
case Active = 'active';

...

// Accessing the value of color:
echo Status::Active->color();
```

### Computed "properties"

There are some cases where values need to be calculated. In those cases, you may just implement a normal method with a match among all options or apply the needed logic to return the data. But that method won't be seen by the methods `toArray()` or some collection static methods. So for that you may implement the protected method `getComputedFields()` that returns an array with all computed values for your enum cases. For example:

```php
/// As you can access your computed value as the other "property" methods, then it doesn't need to be public
protected function getRgb(): array
{
    return sscanf($this->hex(), '#%02x%02x%02x');
}

protected function getComputedFields(): array
{
    return [
        'rgb' => $this->getRgb(),
    ];
}
```

In this case the method `rgb()` can be used to access the color as RGB. Also the value of `rgb` will be available on method `toArray()` or other collection static methods.

### Case classes

TODO: to be described

#### Method `hasClass(): bool`

TODO: to be described

#### Method `getClassName(): ?string`

TODO: to be described

#### Method `getClassInstance(): ?SweetCaseClass`

TODO: to be described

### Collection methods

TODO: to be described

---

## License  

**SweetEnum** is an open-sourced software licensed under the **[MIT license](https://opensource.org/licenses/MIT)**.
