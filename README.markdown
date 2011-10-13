MediaEl - [MediaElement.js HTML5 Video Player plugin for Joomla]
==================================================
Version 0.3

HTML5 <video> / <audio> code generator with fallback to Flash and Silverlight - if desired. 
View [mediaelementjs.com](http://mediaelementjs.com) for a demo of MedialElement.js and overview.

This plugin provides a method for generating the code within Joomla using a shortcode. Compatible with Joomla 1.5. Joomla 1.6 compatible files currently untested. 

The structure of this plugin was based on the VideoJS plugin developed by [Alfred Bösch](http://www.boeschung.de).


Getting Started
---------------

### Step 1: Zip the files into an archive and upload/install to Joomla.
If you get errors it is likely there are hidden files bring included. Often occurs on a Mac - use Terminal to compress or Windows. 


### Step 2: Enable the plugin in Joomla.

  
### Step 3: On the plugin page in Joomla you can set defaults if you want. 
(Optional step).


### Step 4: Insert the shortcode into an article. 

Video:

    {pb_mediael media=[video] width=[640] height=[480] autoplay=[true] preload=[true] loop=[true] video_mp4=[http://mygreatvideofile.mp4] video_webm=[http://mygreatvideofile.webm] video_ogg=[http://mygreatvideofile.ogg] flash=[http://mygreatvideofile.mp4] image=[http://mygreatposterimage.jpg] }

Audio:

    {pb_mediael media=[audio] width=[250] height=[25] audio_mp3=[http://mygreataudiofile.mp3]}
    
All values are optional - except `{pb_medialel }`, providing you have set defaults. However it is recommended to set them in the shortcode. 
If you don't want to encode multiple versions of a movie, MP4 can be played in almost all browsers thanks to HTML5, Flash and Silverlight compatibility. Simply specify the same MP4 source file as both `video_mp4=[ ]` and `flash=[ ]`.





Coming Next
-----------
- Yest under Joomla 1.6
- Add support for more MEJS variables.
- Add support for each player having different setup variables.
- Add pre-compressed versions for immediate use.
- (Permenant..) Clean up and check code. 

Changelog
---------
0.3 (2011-10-13)

- Cleaned up output in Head. Removed codec declaration from <video> src.

0.2 (2011-10-13)

- Added support for Joomla v1.6 (untested)

0.1 (2011-10-13)

- First Release