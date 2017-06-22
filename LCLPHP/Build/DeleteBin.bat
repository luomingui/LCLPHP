@for /r . %%I in (data/log) do if exist "%%I" rd/s/q "%%I"
@for /r . %%I in (data/template) do if exist "%%I" rd/s/q "%%I"
del /s/q/f *.user
del /s/q/f *.suo
