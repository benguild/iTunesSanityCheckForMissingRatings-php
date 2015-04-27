<?php
require('CFPropertyList/classes/CFPropertyList/CFPropertyList.php');
use CFPropertyList\CFPropertyList as CFPropertyList;

ini_set('memory_limit','8G');
date_default_timezone_set('UTC');
////

echo("\n");
echo('########################################'."\n");
echo('This PHP script will find playlists and songs in playlists that are missing from your current iTunes Library but present in one or more backup files that you provide.'."\n");
echo('########################################'."\n\n");

echo('Created by Ben Guild, Copyright (c) 2015'."\n");
echo('NO WARRANTY, USE AT YOUR OWN RISK!'."\n\n");

echo('########################################'."\n\n");
////

if (count($argv)<3)
{
	echo('Usage: php iTunesSanityCheckForMissingPlaylists.php path1 path2'."\n\n");
	
	echo('- path1 = your current, most recent, active `iTunes Music Library.xml` file that is being used by iTunes.'."\n");
	echo('- path2 = path to a single folder at root-level (without subfolders) containing all of the XML files (only!) that you watch to check for playlists that are missing from your current library instance.'."\n\n"); 
	
	echo('Output is currently just any missing playlists or track IDs of missing songs from playlists.'."\n\n"); 
	exit();
	
}
////

$loadDirectory=new DirectoryIterator($argv[2]);
$xmlFiles=array($argv[1]);

foreach ($loadDirectory as $fileinfo)
{
    if (!$fileinfo->isDot() && strtolower($fileinfo->getExtension())=='xml')
    {
	    echo('Found backup file: '.$fileinfo->getPathname()."\n");
	    $xmlFiles[]=$fileinfo->getPathname();
	    
    }
    
}

echo("\n");

if (count($xmlFiles)<2)
{
	exit('Not enough files provided/found for this script to be useful.'."\n");
	
}
////

$currentPlaylists=array();

$playlistsDates=array();

// $playlistsMissing=array();
$playlistsToPatch=array();


foreach ($xmlFiles as $i => $xmlFile)
{
	echo('Loading: '.$xmlFile."...\n");
	
	$plist=new CFPropertyList($xmlFile, CFPropertyList::FORMAT_XML);
	if (!$plist) { exit('Could not load: '.$xmlFile); }
	
    $plist_data=$plist->toArray();
    ////
    
    echo('Found '.number_format(count($plist_data['Playlists'])).' playlist(s) in this file, and the file was created '.date('D M j G:i:s T Y',$plist_data['Date']).'....'."\n");
    
    $thisFoundCount=0;
	
    if (!$i) { echo('Looking for current playlists...'."\n"); }
    else { echo('Looking for playlists not present in the original subset...'."\n"); }
    
    foreach ($plist_data['Playlists'] as $playlist)
    {
	    if (isset($playlist['Visible']) || isset($playlist['Distinguished Kind']) || isset($playlist['Smart Info'])) { continue; }
	    ////
	    
        if (!$i) // Found a playlist in the original subset?
        {
	        $currentPlaylists[$playlist['Playlist Persistent ID']]=$playlist;
	        
        }
        else // In a later subset?
        {
	        if (!isset($currentPlaylists[$playlist['Playlist Persistent ID']])) // Is playlist missing?
	        {
		        echo('+ Playlist "'.$playlist['Name'].'" ('.$playlist['Playlist Persistent ID'].') is entirely missing in current live subset, but present in this file.'."\n");
		        
	        }
	        else if (isset($playlist['Playlist Items']))
	        {
		        $differences=array_diff($playlist['Playlist Items'],$currentPlaylists[$playlist['Playlist Persistent ID']]['Playlist Items']);
		        
		        if (count($differences)) { echo("\n".'+ Playlist "'.$playlist['Name'].'" ('.$playlist['Playlist Persistent ID'].') has the following differences from current live subset:'."\n".print_r($differences,true)."\n"); }
		        
	        }
	        
        }
        
    }
	////
	
	if (!$i)
	{
		echo('Loaded '.count($currentPlaylists).' playlist(s).'."\n");
		
	}
	
    echo("\n");
    
}
////

echo('########################################'."\n");
echo('########################################'."\n\n");

exit('Finished scanning library archives'."\n");

?>