*Background & instructions are on my blog: http://benguild.com/2015/02/14/how-to-restore-disappearing-itunes-song-star-ratings*

---------------

This PHP script will find songs in your iTunes Library without star ratings, and will then scour your specified backup files to find the most recent star rating for that file in case it was somehow removed or lost somewhere along the way for some reason. It will generate an AppleScript as output that can be run on your Mac to automatically re-set all of the missing ratings!

Created by Ben Guild, Copyright © 2015. ABSOLUTELY NO WARRANTY. USE THIS SCRIPT AND THE FILES THAT IT GENERATES AT YOUR OWN RISK! BACKUP YOUR DATA!!

---------------

# Usage:

In Terminal, run this script:

`php iTunesSanityCheckForMissingRatings.php path1 path2`
- **`path1`** = your current, most recent, active `iTunes Music Library.xml` file that is being used by iTunes.
- **`path2`** = path to a single folder at root-level (without subfolders) all the XML files (only!) that you watch to check for past ratings.

Output is a date-suffixed *AppleScript* that can be run on your Mac to set the missing star ratings in iTunes.
