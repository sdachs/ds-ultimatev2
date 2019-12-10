<?php

return array (
  'attackPlanner' => 
  array (
    'export' => 
    array (
      'BB' => 
      array (
        'default' => 
        array (
          'body' => '[u][size=12][b]%TITLE%[/b][/size][/u]

[b]Attackplan[/b]
Attackcount: %ELEMENT_COUNT%
[table]
[**]ID[||]Typ[||]Unit[||]Start[||]Target[||]Send time[||]Place[/**]
%ROW%
[/table]',
          'row' => '[*]%ELEMENT_ID%[|]%TYPE%[|]%UNIT%[|]%SOURCE%[|]%TARGET%[|]%SEND%[|]%PLACE%[/*]',
        ),
      ),
      'IGM' => 
      array (
        'default' => 
        array (
          'row' => '%TYPE% by [b]%ATTACKER%[/b] from %SOURCE% with %UNIT% at [b]%DEFENDER%[/b]  on %TARGET% starts on [color=#ff0e0e]%SEND%[/color] and arrives on [color=#2eb92e]%ARRIVE%[/color] (%PLACE%)',
        ),
      ),
    ),
    'exportBB' => 'Export Forum',
    'exportBBDesc' => 'Export for the Forum',
    'exportIGM' => 'Export IGM',
    'exportIGMDesc' => 'Export for IGM without Images',
    'exportWB' => 'Export DSWorkbench',
    'exportWBDesc' => 'Export for DSWorkbench',
    'show' => 'Show attackplann',
  ),
  'map' => 
  array (
    'allySelectPlaceholder' => 'Select an ally',
    'playerSelectPlaceholder' => 'Select a player',
    'copy' => 'Copy',
    'edit' => 'Edit Markers',
    'editLink' => 'Edit Link',
    'editLinkDesc' => 'Everybody with this link can edit the Map. Be careful!',
    'links' => 'Links for sharing the map',
    'settings' => 'Generation settings',
    'showLink' => 'View Link',
    'showLinkDesc' => 'This Links gives only show access to the Map',
    'title' => 'World Map',
    'defaultBarbarian' => 'Default colour for Barbarian villages that are not marked',
    'defaultPlayer' => 'Default colour for Player villages that are not marked',
    'showBarbarian' => 'Show barbarian villages',
    'showPlayer' => 'Show player villages',
    'defaultBackground' => 'Background colour',
    'center' => 'Center of created map',
    'zoom' => 'Zoom',
    'ally' => 'Mark an ally',
    'player' => 'Mark a player',
    'village' => 'Mark a village',
    'forumLink' => 'Forum Link',
    'forumLinkDesc' => 'Use these BB-Codes to show this map to other players in forums',
    'showAllText' => 'all markers',
    'deleteDrawing' => 'delete drawing',
    'drawing' => 'drawing',
    'drawer' => 
    array (
      'general' => 
      array (
        'addDrawer' => 'Add Drawer',
        'deleteCanvas' => 'Delete this canvas',
        'deleteCanvasConfirm' => 'Are you sure want to delete this canvas?',
        'eraser' => 'Eraser',
        'freeDrawing' => 'Free drawing mode',
        'insert' => 'Insert',
        'insertDrawer' => 'Insert Drawer',
        'simpleEraser' => 'SimpleWhiteEraser',
      ),
      'canvas' => 
      array (
        'size' => 'Size (px)',
        'position' => 'Position',
        'inline' => 'Inline',
        'left' => 'Left',
        'center' => 'Center',
        'right' => 'Right',
        'floating' => 'Floating',
        'canvasProp' => 'Canvas properties',
        'background' => 'Background',
        'cancel' => 'Cancel',
        'save' => 'Save',
        'transparent' => 'transparent',
      ),
      'fullscreen' => 
      array (
        'enter' => 'Enter fullscreen mode',
        'exit' => 'Exit fullscreen mode',
      ),
      'shape' => 
      array (
        'bringForward' => 'Bring forward',
        'bringBackwards' => 'Send backwards',
        'bringFront' => 'Bring to front',
        'bringBack' => 'Send to back',
        'duplicate' => 'Duplicate',
        'remove' => 'Remove',
      ),
      'brush' => 
      array (
        'size' => 'Size',
      ),
      'color' => 
      array (
        'fill' => 'Fill:',
        'transparent' => 'Transparent',
      ),
      'border' => 
      array (
        'border' => 'Border:',
        'none' => 'None',
      ),
      'arrow' => 
      array (
        'drawSingle' => 'Draw an arrow',
        'drawTwo' => 'Draw a two-sided arrow',
        'tooltip' => 'Lines and arrows',
      ),
      'circle' => 
      array (
        'tooltip' => 'Draw a circle',
      ),
      'line' => 
      array (
        'tooltip' => 'Draw a line',
      ),
      'rect' => 
      array (
        'tooltip' => 'Draw a rectangle',
      ),
      'triangle' => 
      array (
        'tooltip' => 'Draw a triangle',
      ),
      'polygon' => 
      array (
        'tooltip' => 'Draw a Polygon',
        'stop' => 'Stop drawing a polygon (esc)',
        'newLine' => 'Click to start a new line',
      ),
      'text' => 
      array (
        'tooltip' => 'Draw a text',
        'font' => 'Font:',
        'newText' => 'Click to place a text',
      ),
      'moveable' => 
      array (
        'moveCanvas' => 'Move canvas',
      ),
      'base' => 
      array (
        'tooltip' => 'Click to start drawing a',
      ),
    ),
    'show' => 'Show map',
    'markerFactor' => 'Village spacing',
  ),
);
