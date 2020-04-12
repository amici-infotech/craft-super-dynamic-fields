# Super Dynamic Fields Plugin for Craft CMS 3

Instead of adding key/values in field settings, You can now include any Craft CMS template where you can add JSON variable for options. If you have tons of fields with options and you dont want to add options in each field, this is best plugin for you. This field produce Checkboxes, Radio buttons, Select dropdown (single or multiple) selections. This fieldtype supports backend and frontend channel forms including normal fields, GRID and Fluid field types. Most important use is, As it comes from template, You can use any Craft CMS tags to generate options. This Addon just not stick with label and value fields for option. It gives you custom field support too. In the same JSON, you can pass any variables and fetch those variable at frontend with simple Craft CMS tags. Find more info on our docs.


---
### Installation
Open your terminal and go to your Craft project:

```bash
cd /path/to/project
```
Run this command to load the plugin:

```bash
composer require amici/craft-super-dynamic-fields
```

In the Control Panel, go to Settings → Plugins and click the “Install” button for Super Dynamic Fields.

---
### Usage
Create new field and select any of super dynamic field (Checkboxes, Radio or Dropdown) and scroll to the settings. You have to select JSON template you have added options of and that's it!

You have to follow proper JSON format to add options. There is 2 required JSON fields. i.e., "label" and "value". You can use "default" as "yes" to make any option as default selected. Example of JSON:

```bash
[
	{"value" : "option1", "label" : "Option 1"},
	{"value" : "option2", "label" : "Option 2", "default" : "yes"},
	{"value" : "option3", "label" : "Option 3"},
]
```

"value", "label" and "default" is pre defined variables of JSON. You can also pass anything else in JSON that will become varibale custom field automatically.

```bash
[
	{"label": "--" , "value" : "" , "default" : "yes", "link" : ""},
	{"label": "Large" , "value" : "lg", "link" : "<a href='/abc'>Click Here</a>"},
	{"label": "Medium" , "value" : "md", "link" : "<a href='/xyz'>Click Here</a>"}
]
// In this example, link is a custom field.
```

```bash
{% set entries = craft.entries.section("sectionName").limit(11).all() %}
[
	{"label": "--" , "value" : "" , "default" : "yes", "url_title" : ""},
	{% for item in entries %}
		{"label": "{{ item.title }}" , "value" : "{{ item.getId() }}", "slug" : "{{ item.slug }}"}
		{% if not loop.last %},{% endif %}
	{% endfor %}
]
// In this example, slug is a custom field.
```

### Documentation
Visit the [Super Dynamic Fields](https://docs.amiciinfotech.com/craft-cms/super-dynamic-fields-craft) for all documentation, guides, pricing and developer resources.

### Support
Get in touch with us via the [Amici Infotech Support](https://amiciinfotech.com/contact) or by [creating a Github issue](https://github.com/amici-infotech/craft-super-dynamic-fields/issues)
