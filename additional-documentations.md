# Drupal Able Player Module Documentation

Further enchancements to the [Drupal Able Player](https://www.drupal.org/project/ableplayer) created by Tony Cao for the UTSC Digital Scholarship Unit.

## Multiple Transcript Player

**Purpose:** Correct the bug in order to allow for the correct playing of multiple language transcripts alongside the audio/video.

**Relevant Code Modification:** The following modification in `ableplayer.min.js` within the function `AblePlayer.prototype.setupTracks` were made in order to achieve this:

```diff
...
(function (a, n) {
    a.src;
    return function (t, e) {
+        var vtt;
        var i = e,
        vtt = l.parseWebVTT(t, i);

        // Setup the language track and label for the captions
+        a.language = !jQuery.isEmptyObject(vtt.metadata)
+            ? vtt.metadata.lang.trim()
+            : "en";
+        a.label = l.getLanguageName(a.language);

        var r = a.language,
            o = a.label;
        s = vtt.cues;
    ...
```

_WebVTT Format:_ The following is a sample WebVTT file to specify a French transcript file (Note: it defaults to English if there is no language specified)

```
WEBVTT
lang:fr

00:00:00.500 --> 00:00:04.000
J'ai appris à faire demi-tour et tu es revenu

00:00:04.100 --> 00:00:06.000
de l'espace lointain
...
```

## New Configuration Options/Menu

**Purpose:** New settings for the able player. Namely two new settings: fixed vs. draggable transcript container and having the transcript container be opened by default.

### File: AbleplayerAudioFormatter.php

Two new form fields _viewer_ and _transcript_ along with several functions that are overrided to accomodate the new form fields.

```
/**
* {@inheritdoc}
*/
public static function defaultSettings() {
    return [
        'viewer' => FALSE,       /* New field */
        'transcript' => FALSE,   /* New field */
        'controls' => FALSE,
        'autoplay' => FALSE,
        'loop' => FALSE,
    ] + parent::defaultSettings();
}

/**
* {@inheritdoc}
*/
public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
        'viewer' => [
        '#title' => $this->t('Display Transcript Container by Default'),
        '#type' => 'checkbox',
        '#default_value' => $this->getSetting('viewer'),
        ],
        'transcript' => [
        '#title' => $this->t('Draggable Transcript Container'),
        '#type' => 'checkbox',
        '#default_value' => $this->getSetting('transcript'),
        ]
    ] + parent::settingsForm($form, $form_state);
}

/**
* {@inheritdoc}
*/
protected function prepareAttributes(array $additional_attributes = []) {
    return parent::prepareAttributes(['viewer','transcript']);
}

/**
* {@inheritdoc}
*/
public function settingsSummary() {
    $summary = parent::settingsSummary();
    $summary[] = $this->t('Display Transcript Container by Default: %viewer', [
        '%viewer' => $this->getSetting('viewer') ? $this->t('yes') : $this->t('no'),
    ]);
    $summary[] = $this->t('Draggable Transcript Container: %transcript', [
          '%transcript' => $this->getSetting('transcript') ? $this->t('yes') : $this->t('no'),
    ]);
    return $summary;
}

```

### File: AbleplayerVideoFormatter.php

Two new form fields _viewer_ and _transcript_ along with several functions that are overrided to accomodate the new form fields.

```
/**
* {@inheritdoc}
*/
public static function defaultSettings() {
    return [
      'viewer' => FALSE,
      'transcript' => FALSE,
      'controls' => FALSE,
      'autoplay' => FALSE,
      'loop' => FALSE,
    ] + parent::defaultSettings();
}

/**
* {@inheritdoc}
*/
public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
        'viewer' => [
        '#title' => $this->t('Display Transcript Container by Default'),
        '#type' => 'checkbox',
        '#default_value' => $this->getSetting('viewer'),
        ],
        'transcript' => [
        '#title' => $this->t('Draggable Transcript Container'),
        '#type' => 'checkbox',
        '#default_value' => $this->getSetting('transcript'),
        ]
    ] + parent::settingsForm($form, $form_state);
}

/**
* {@inheritdoc}
*/
protected function prepareAttributes(array $additional_attributes = []) {
    return parent::prepareAttributes(['viewer', 'transcript']);
}

/**
* {@inheritdoc}
*/
public function settingsSummary() {
    $summary = parent::settingsSummary();
    $summary[] = $this->t('Display Transcript Container by Default: %viewer', [
      '%viewer' => $this->getSetting('viewer') ? $this->t('yes') : $this->t('no'),
    ]);
    $summary[] = $this->t('Draggable Transcript Container: %transcript', [
        '%transcript' => $this->getSetting('transcript') ? $this->t('yes') : $this->t('no'),
    ]);
    return $summary;
}
```

### File: ableplayer.min.js

```diff
window.AblePlayer:
    ...
+   void 0 !== o(t).attr("transcript")
+       ? (this.dragTranscript = !0)
+       : (this.dragTranscript = !1),
+   void 0 !== o(t).attr("viewer")
+       ? (this.transcriptViewerDefault = !0)
+       : (this.transcriptViewerDefault = !1),
    ...

AblePlayer.prototype.updatePrefs:
    ...
    0=== this.prefHighlight &&
        this.$transcriptDiv.find("span").removeClass("able-highlight"),
    this.updateCaption(),
    (this.refreshingDesc = !0),

    /* Check for new preferences update and then reload if necessary */
    this.initDescription(),
+   this.injectTranscriptArea(), location.reload();
    ...


AblePlayer.prototype.initDragDrop:
    /* Check if it was selected to have a dragable transcript container */
+   if (this.dragTranscript) {
        var i, s, t, a, n, r;
        (i = this),
        ...
        ...
+   }
    ...


AblePlayer.prototype.injectTranscriptArea:
    ...
+   this.transcriptViewerDefault
+       ? this.$transcriptArea.show()
+       : this.$transcriptArea.hide(),
+   this.prefTranscript || this.transcriptDivLocation;
-   this.prefTranscript || this.transcriptDivLocation || this.$transcriptArea.hide();
    ...


AblePlayer.prototype.endDrag:
    ...
    "keyboard" === this.dragDevice && i.focus(),
    (this.dragging = !1),

    /* Remove cookie to allow for page refresh and transcript area "reset" when changing between fixed and draggable */
-   this.updateCookie(t),

    (this.startMouseX = void 0),
    (this.startMouseY = void 0),
    ...
```

## Timestamp for Transcript Container

**Purpose:** Display transcript cues along with a time stamp and have the cues be displayed in a separate box for each similar to the old player.

**Relevant Code Modification:** The following modification in `ableplayer.min.js` were made to accomodate this

```diff
...
AblePlayer.prototype.generateTranscript:
    ...
    for (
        var a = o(e.components.children[s]), n = 0;
        n < a.length;
        n++
        ) {
            var r = a[n];
            "string" == typeof r &&
            (h.lyricsMode
            ? (r = r.replace("\n", "<br>") + "<br>")
            : (r += " ")),
            i.append(r);
            }
+           i.prepend("<i>[" + h.formatSecondsAsColonTime(e.start) + "]</i>  &nbsp;&nbsp;"),
            i.attr("data-start", e.start.toString()),
            i.attr("data-end", e.end.toString()),
    ...
```

Along with the following CSS formatting in `ableplayer.min.css`:

```diff
    ...
-   .able-transcript div {
-       margin: 1em 0;
-   }

+   .able-transcript-caption {
+       border-top: 1px solid #ddd;
+       border-bottom: 1px solid #ddd;
+       border: 1px solid #ddd;
+       display: block;
+       margin: 0px;
+       margin-bottom: -1px;
+   }
    ...
```

## Other Documentation

### Default Settings:

- Default width of the transcript container matches the width of the able player

### Configuration Menu:

The configuration menu for Ableplayer can be found by going to `Structure > Media Type > Video > Manage Display > Video File` then selecting on the settings gear. Same steps for audio files by choosing the audio option under media type.

Available Configurations Include:

- `Display Transcript Container by Default`: Display the transcript container by default (i.e. open upon landing on site)
- `Draggable Transcript Container`: Enable a draggable transcript container (i.e. the built-in one)
- `Show playback controls`: Double playback buttons
- `Autoplay`: Automatically start playing the media when landing on that page
- `Loop`: Repeat the media when over

### Transcript Text Formatting:

Transcript chunks are split based based off the presence of a **speaker** (i.e. it splits based off speaker chunks).
Example:

```
WEBVTT
kind:captions
lang:en

1
00:00:00.500 --> 00:00:04.000
[Dog:] I learned to turn around and you came back

2
00:00:04.100 --> 00:00:06.000
from outer space

3
00:00:06.100 --> 00:00:10.500
and now I see that you are here in a low mood

4
00:00:10.600 --> 00:00:14.000
[More Dog:] I should have moved and taken your key

5
00:00:14.100 --> 00:00:17.500
if he knew he was going to come back and make me sick

6
00:00:17.600 --> 00:00:19.500
<v Tony:> now go, get out of here

7
00:00:19.600 --> 00:00:21.500
Frank!

```

will render as

```
[Dog:] I learned to turn around and you came back from outer space and now I see that you are here in a low mood

[More Dog:] I should have moved and taken your key if he knew he was going to come back and make me sick

(Tony:) now go, get out of here Frank!
```

while

```
WEBVTT
kind:captions
lang:en

1
00:00:00.500 --> 00:00:04.000
[Dog:] I learned to turn around and you came back

2
00:00:04.100 --> 00:00:06.000
from outer space

3
00:00:06.100 --> 00:00:10.500
and now I see that you are here in a low mood

4
00:00:10.600 --> 00:00:14.000
I should have moved and taken your key

5
00:00:14.100 --> 00:00:17.500
if he knew he was going to come back and make me sick

6
00:00:17.600 --> 00:00:19.500
<v Tony:> now go, get out of here

7
00:00:19.600 --> 00:00:21.500
Frank!

```

will render as

```
[Dog:] I learned to turn around and you came back from outer space and now I see that you are here in a low mood
I should have moved and taken your key if he knew he was going to come back and make me sick

(Tony:) now go, get out of here Frank!
```
