@echo off
::ѹ��Ӧ��·����PS:Ŀ¼���������ģ���󲻴�\
@set /p dir_path=����ѹ��Ŀ¼:
::ѹ��Ӧ��·��
@set AJAXZIPPath="D:\Program Files\Microsoft\Microsoft Ajax Minifier 4\"
::��������׺
@set file_temp=.js

::ʹ����Ŀ¼
rd    build_temp /s /Q
mkdir  build_temp
xcopy  %dir_path%\*.js build_temp /s /r /y
echo %dir_path%\build_temp
cd /D build_temp

rem ::ֱ�ӽ���Ŀ¼
rem cd /D %dir_path%
::����ѹ��Ӧ��
call %AJAXZIPPath%AjaxMinCommandPromptVars.bat

::ѹ��JS�ļ�
for /R %%i in (*.js) do (
	echo %%i
	COPY %%i %%i%file_temp%
	del %%i
	ajaxmin -h %%i%file_temp% -o %%i
	rem COPY %%i%file_temp% %%i
	del %%i%file_temp%
)

pause
