#include <Array.au3>
#include <GuiTreeView.au3>

Dim $workingDir = $CmdLine[1]
Dim $genrsaPath = $CmdLine[2]
Dim $finess     = $CmdLine[3]

ProcessClose("WGENRSA.exe");
Run($genrsaPath);

Const $genrsaTitle = "GENRSA 10.9.9.9";

WinWaitActive($genrsaTitle);


;TODO ne pas définir la période comme cela, (la comno n'est pas présente en 2011).
ControlSetText($genrsaTitle, "", "[NAME:comboPeriode]", "Période de test (M0)");

ControlSetText($genrsaTitle, "", "[NAME:ztPathRss]", $workingDir  & "/rss");
ControlSetText($genrsaTitle, "", "[NAME:tbFicUMImport]", $workingDir  & "/autorisations");
ControlSetText($genrsaTitle, "", "[NAME:ztPathAno]", $workingDir  & "/anohosp");

;on décoche la case utilisation du  fichier hosp-pmsi
ControlClick($genrsaTitle, "", "[NAME:chbHospPmsi]") 


ControlClick($genrsaTitle, "", "[NAME:btTraitChoixUm]")


$umTitle = "Renseignements des données sur les unités médicales";
WinWait($umTitle);
ProcessClose("notepad.exe");

ControlClick($umTitle, "", "[NAME:btValid]");

$newGenrsaTitle = "GENRSA 10.9.9.9  [" & $finess & "]  [Période de test (M0)]  [2011]";

WinWaitActive($newGenrsaTitle);

ControlCommand($genrsaTitle, "", "[NAME:chbHospPmsi]", "UnCheck", "") 


ControlClick($newGenrsaTitle, "", "[NAME:btLant]")

WinWaitActive("Fin de traitement")
ControlClick("Fin de traitement", "", "[CLASS:Button; INSTANCE:1]")


WinWaitActive("[CLASS:Notepad]")
WinClose("[CLASS:Notepad]")
WinWaitActive("[CLASS:Notepad]")
WinClose("[CLASS:Notepad]")
ProcessClose("notepad.exe");


WinWaitActive($newGenrsaTitle)
ControlClick($newGenrsaTitle, "", "[NAME:btExp]")

if @OSLang == '040C' Then
  Dim $browseFolderTitle = 'Rechercher un dossier'
  Else
  Dim $browseFolderTitle = 'Browse For Folder'
EndIf

WinWaitActive($browseFolderTitle)
ControlClick($browseFolderTitle, "", "[CLASS:Button; INSTANCE:2]")

WinWaitActive("Export")
ControlClick("Export", "", "[CLASS:Button; INSTANCE:1]")
ProcessClose("WGENRSA.exe");
Exit(0);
