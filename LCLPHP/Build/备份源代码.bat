::�Զ����ݵ�ǰ�ļ���
::by luomg, 21:15 2010-10-13
::minguiluo@163.com
@echo off

set "Ymd=%date:~,4%%date:~5,2%%date:~8,2%"
md F:\Github2017\backup\LCL.PHP_%ymd%
@echo �Զ����ݵ�ǰ�ļ���

xcopy /e /y "F:\Github2017\LCLPHP" F:\Github2017\backup\LCL.PHP_%ymd%

@echo ���ݱ�����ɣ�3�������˳���  
ping /n 3 127.0.0.1 >nul  
::exit
@pause


