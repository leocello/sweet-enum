---
name: sweet-enum-development
description: Build and work with SweetEnum backed enums, including custom properties via PHP Attributes, computed fields, case classes, and collection-style iteration.
---

# SweetEnum Development

## When to use this skill

Use this skill when working with backed PHP enums in a Laravel (or plain PHP) project that uses the `leocello/sweet-enum` package. Apply it when creating new enums with custom properties, adding computed fields, delegating per-case logic to dedicated classes, or iterating over enum cases as collections.

## Core structure

Every SweetEnum must:

1. Be a **backed enum** (`string` or `int`).
2. Implement `SweetEnumContract`.
3. Use the `SweetEnum` trait.
4. Decorate each case with the `#[SweetCase(...)]` attribute.
5. Add PHPDoc @method annotations for each case custom property.
6. Optionally define a `DEFAULT` constant.

```php
use Leocello\SweetEnum\SweetCase;
use Leocello\SweetEnum\SweetEnum;
use Leocello\SweetEnum\SweetEnumContract;

/**
 * @method string color()
 */
enum Status: string implements SweetEnumContract
{
    use SweetEnum;
    
    public const DEFAULT = self::Active;

    #[SweetCase(
        title: 'Active',
        color: 'green',
    )]
    case Active = 'active';

    #[SweetCase(
        title: 'Inactive',
        color: 'red',
        isOn: false,   // marks this case as inactive (hidden from active-only queries)
    )]
    case Inactive = 'inactive';
}
```

## SweetCase attribute parameters

| Parameter    | Type      | Default                  | Description                                         |
|--------------|-----------|--------------------------|-----------------------------------------------------|
| `title`      | `?string` | `null` (uses case name)  | Human-readable label for the case.                  |
| `isOn`       | `bool`    | `true`                   | Whether the case is active/enabled.                 |
| `caseClass`  | `?string` | `null`                   | FQCN of a `SweetCaseClass` subclass for this case.  |
| `...$custom` | `mixed`   | —                        | Any extra named arguments become custom properties. |

## Accessing properties on a case

All built-in and custom properties are accessed as zero-argument methods:

```php
Status::Active->id();     // 'active'   (alias of ->value)
Status::Active->name();   // 'Active'   (alias of ->name)
Status::Active->title();  // 'Active'
Status::Active->isOn();   // true
Status::Active->color();  // 'green'    (custom property)

// Missing property → returns null by default
Status::Active->unknown();              // null
Status::Active->unknown('fallback');    // 'fallback'
Status::Active->unknown(default: 'hi'); // 'hi'
Status::Active->unknown(strict: true);  // throws InvalidArgumentException
```

## Default case

Define the `DEFAULT` constant to set a specific case as the default. If omitted, the **first declared case** is used:

```php
public const DEFAULT = self::Active;
```

## Static enum methods

```php
Status::isSameAs(Status::class);   // true – class identity check

Status::getCases();                // active cases only (isOn = true)
Status::getCases(false);           // all cases including inactive

Status::getCasesInfo();                      // [['id'=>..., 'title'=>...], ...]
Status::getCasesInfo(SweetFields::Full);     // full info per case
Status::getCasesInfo(['id', 'color']);       // custom field selection

Status::getDefaultCase();          // the DEFAULT case (or first)
Status::getRandomCase();           // a random active case
Status::getRandomCase(false);      // a random case, including inactive
```

## Instance methods

```php
$status = Status::Active;

$status->isA(Status::class);   // true  – type check
$status->isA(Color::class);    // false

$status->is(Status::Active);                       // true
$status->is(Status::Inactive);                    // false
$status->is([Status::Active, Status::Inactive]);  // true (matches any)

$status->toArray();                    // ['id' => 'active', 'title' => 'Active']
$status->toArray(SweetFields::Full);   // all fields
$status->toArray(['id', 'color']);     // custom field selection
```

## SweetFields enum

Those are the keys returned by `getCasesInfo()` and `toArray()`:

| Case                | Returned keys                                            |
|---------------------|----------------------------------------------------------|
| `Original`          | `value`, `name`                                          |
| `Basic` *(default)* | `id`, `title`                                            |
| `BasicWithStatus`   | `isOn`, `id`, `title`                                    |
| `Sweet`             | `id`, `title` + all custom & computed fields             |
| `Full`              | `isOn`, `value`, `id`, `name`, `title` + custom & computed |

## Computed properties

For values that must be calculated at runtime, override `getComputedFields()`:

```php
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

The key (`rgb`) becomes accessible as `$case->rgb()` and is included in `SweetFields::Sweet` / `Full` output automatically.

## Case classes

Delegate per-case logic to dedicated classes that extend `SweetCaseClass`:

```php
// Optional default class for all cases without a specific one:
public const DEFAULT_CASE_CLASS = AnimalCaseClass::class;

// In the enum – specific case class:
#[SweetCase(
    caseClass: AnimalCatCaseClass::class,
    title: 'Cat',
)]
case Cat = 'cat';
```

```php
// Base case class
class AnimalCaseClass extends SweetCaseClass
{
    public function sound(): string
    {
        return 'silence';
    }
}

// Per-case override
class AnimalCatCaseClass extends AnimalCaseClass
{
    public function sound(): string
    {
        return 'Meow!';
    }
}
```

Case class methods are called directly on the enum case:

```php
Animal::Cat->sound();   // 'Meow!'
Animal::Sheep->sound(); // 'silence' (uses DEFAULT_CASE_CLASS)
```

Inside a case class, access the associated enum case via `$this->case`.

Case class introspection methods (on the enum case):

```php
$case->hasClass();         // bool – has any (specific or default) class?
$case->getClassName();     // ?string – FQCN or null
$case->getClassInstance(); // ?SweetCaseClass instance or null
```

## Collection iteration

```php
// foreach – iterate with a callback, no return value
Status::foreach(function (Status $case) {
    echo $case->title();
});

// map – returns array keyed by case id
$labels = Status::map(fn (Status $case) => $case->title());

// reduce – accumulates a value across cases
$csv = Status::reduce(function (?string $carry, Status $case) {
    return ($carry ? $carry . ',' : '') . $case->id();
});

// Pass false as second argument to include inactive cases in any collection method
Status::foreach(fn ($c) => ..., false);
Status::map(fn ($c) => ..., false);
Status::reduce(fn ($carry, $c) => ..., false);
```

## Common patterns

### Form select options

```php
$options = Status::getCasesInfo(); // [['id' => 'active', 'title' => 'Active'], ...]
```

### Comparing an enum case

```php
if ($model->status->is(Status::Active)) {
    // ...
}
```

### Including inactive cases (e.g. for seeding)

```php
Status::foreach(function (Status $case) {
    // process all cases
}, onlyActive: false);
```
