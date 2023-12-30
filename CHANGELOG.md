# Changelog

## 2.0.1 - 2023-12-30
> {warning} We have removed `current` variable from json template that will break after upgrade for sites which uses `current` variable in their json templates.

- Solved a bug where new entry was throwing 500 internal server error due to infinite loading element.
- Changed Dropdown UI so we can search and pick dropdown values just like native craft 4 dropdown field.
- Changed Multi-Select Buttons Group UI so we can search and pick values just like native craft 4 dropdown field.
- Changed Radio Buttons Group UI.
- Changed Checkboxes Buttons Group UI.
- Removed existing `current` variable in dynamic loaded sdf json templates.
- Added new `element` variable in dynamic loaded sdf json templates that returns element directly. so we can now use `element` instead `current.element`.
```
{% set entry = element ?? null %}
{% set matrixField = entry.matrixField.all() ?? null %}
[
    { "label" : "-- Select -- ", "value": "" }
    {% for item in matrixField %}
    ,{ "label" : "{{ item.heading }}", "value": "{{ item.getId() }}" }
    {% endfor %}
]
```

## 2.0.0.5 - 2023-06-14
- Fixed a bug with rendering as include template instead of full page template.

## 2.0.0.4 - 2023-06-13
- Fixed a bug where Template was not parsing when field triggers from another plugin or component and that was throwing Memory limit issue.
- Removed Mode and added PATH for template parsing

## 2.0.0.3 - 2023-06-09
- Fixed a bug where in conditional rules, options for SDF were not showing.
- Added cachedOptions field in field settings. Turn that setting while creating field will cache json options and will not render JSON parse from template again and again. That will make field load fast.

## 2.0.0.2 - 2023-04-28
- Removed min version required dependancy.

## 2.0.0.1 - 2022-12-10
- now new `current` object is available inside SDF templates where you can grab current ELEMENT and use all fields inside that element.
```
{% set entry = current.element ?? null %}
{% set matrixField = entry.matrixField.all() ?? null %}
[
    { "label" : "-- Select -- ", "value": "" }
    {% for item in matrixField %}
    ,{ "label" : "{{ item.heading }}", "value": "{{ item.getId() }}" }
    {% endfor %}
]
```

If you are in child element such as matrix, You can go to parent entry by `owner` object.
```
{% set entry = current.element.owner ?? null %}
{% set matrixField = entry.matrixField.all() ?? null %}
[
    { "label" : "-- Select -- ", "value": "" }
    {% for item in matrixField %}
    ,{ "label" : "{{ item.heading }}", "value": "{{ item.getId() }}" }
    {% endfor %}
]
```

## 2.0.0 - 2022-05-10
- Upgraded to support craft cms 4.

> {warning} Super Dynamic Fields now requires PHP 8.0.2 or newer.

> {warning} Super Dynamic Fields now requires Craft CMS 4.0.0 or newer.

## 1.0.7 - 2022-02-09
- Changed DB schema for Multi Select and Checkboxes fieldtypes from string to text.

## 1.0.6 - 2021-08-24
- Added GraphQL Support.

## 1.0.5 - 2020-10-30
- Solved issue where in preview entry mode, Fields were not working.
- Solved issue where adding new matrix block not add default value to fields.
- Change the way to store Multi select and Checkboxes in database.

## 1.0.4 - 2020-10-06
- Solved Error where in List entries table, SDF Checkboxes and Multi Select were throw 503 error.

## 1.0.3 - 2020-04-13
- Typo mistakes in Field Setting page.

## 1.0.2 - 2020-04-12
- Typo mistakes in Field Setting page.

## 1.0.1 - 2020-04-12
- Change SVG Icon.
- Multi option fields enhancements.

## 1.0.0 - 2020-04-12
- Initial release.
