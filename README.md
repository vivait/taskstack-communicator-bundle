# TaskStack Communicator

Send issues direct from a symfony app directly to an instance of TaskStack.

## Config
### Import routes
```yaml
vivait_taskstack_communicator:
    resource: "@VivaitTaskstackCommunicatorBundle/Resources/config/routing.yml"
    prefix:   /
```

### Configuration
```yaml
vivait_taskstack_communicator:
    api_key: myapikey
    url: http://mycompany.taskstack.uk
```
