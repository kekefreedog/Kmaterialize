# Sass Fix Issues

## Error on Windows

### Error
- If you get the bellow error message when you executed Sass :
```shell
sass : File C:\Users\Freedog Web\AppData\Roaming\npm\sass.ps1 cannot be loaded because running scripts is disabled on this system. For more information, see about_Execution_Policies at 
https:/go.microsoft.com/fwlink/?LinkID=135170.
```

### Solution
- First Execute this code : ``Get-ExecutionPolicy -List``
- Then execute this code : ``Set-ExecutionPolicy -Scope LocalMachine Unrestricted``
