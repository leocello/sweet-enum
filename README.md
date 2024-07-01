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

TODO: to be described

#### `getCasesInfo(array|string $fields = SweetEnumContract::FIELDS_SWEET_BASIC, bool $onlyActives = true): array`

TODO: to be described

#### `getDefaultCase(): SweetEnumContract`

TODO: to be described

#### `getRandomCase(): SweetEnumContract`

TODO: to be described

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

TODO: to be described

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
