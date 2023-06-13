# Changelog

## 1.0.7.3 - 2023-06-13
- Fixed a bug where Template was not parsing when field triggers from another plugin or component and that was throwing Memory limit issue.

## 1.0.7.2 - 2023-06-09
- Added cachedOptions field in field settings. Turn that setting while creating field will cache json options and will not render JSON parse from template again and again. That will make field load fast.

## 1.0.7.1 - 2022-12-10
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
