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

## Usage

In your bootstrap nav bar, add 

```twig

<ul class="nav navbar-nav pull-right right-navbar-nav">
    <li>...</li>
    
    ...
   
    {{ render(path('vivait_taskstack_communicator_panel')) }}

    ...
    
    <li>...</li>
</ul>
```

In a new or existing javascripts include add:

```twig
{% javascripts
"@VivaitTaskstackCommunicatorBundle/Resources/public/js/communicator.js"
%}

<script type="text/javascript" src="{{ asset_url }}"></script>
{% endjavascripts %}
```
