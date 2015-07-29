# TaskStack Communicator

Send issues direct from a symfony app directly to an instance of TaskStack.

## Configuration
### `routing.yml`
```yaml
vivait_taskstack_communicator:
    resource: "@VivaitTaskstackCommunicatorBundle/Resources/config/routing.yml"
    prefix:   /
```

### `config.yml`
```yaml
vivait_taskstack_communicator:
    api_key: myapikey
    url: http://mycompany.taskstack.uk
```

## Help Form

Add a help form to your web page.

Simply call the twig extension to render the help form

```twig
{{ communicator_help_panel() }}
```

To allow the form to submit via AJAX, add the javascript:

```twig
{% javascripts
"@VivaitTaskstackCommunicatorBundle/Resources/public/js/helpPanel.js"
%}

<script type="text/javascript" src="{{ asset_url }}"></script>
{% endjavascripts %}
```
