# Video Link

Video Link is a Grid-compatible Field Type add-on for ExpressionEngine that
enables easy and user-friendly embedding of user-defined YouTube and Vimeo
videos.

Have you ever asked your users to input just the code of a YouTube video into
an ExpressionEngine text field? Or allowed the user to paste in the entire URL
of a YouTube video, but then had to parse out the code in order to do
something useful? Then Video Link is what you need.

## A note about support

While we have incentive to keep this project working because we use it
frequently, we are not always available to provide support for the Video Link
plugin. We therefore offer it to you, free of charge, but with no guarantee of
support. Find something that's not working? Or could be improved? By all
means, fix it! Submit a pull request, and we'll pull it into the project so
everyone can benefit. But please, no hard feelings if we can't help you when
it's not working. Go forth and Open Source.

## Requirements

* EE 2.7

## Installation

1. Copy the "system/expressionengine/third_party/videolink" folder to
ExpressionEngine's third-party add-ons directory. (e.g.
`/system/expressionengine/third_party/`)
2. Copy the "themes/third_party/videolink" folder to ExpressionEngine's third-
party themes directory. (e.g. `/themes/third_party/videolink/`)
3. Log into your ExpressionEngine control panel.
4. Open the Field Types tab.
5. Click "Install" by "Video List".

## Usage

Set "Video Link" as the Field Type.

## Tags

* `{your-field-name}` – The entire URL that the user typed or pasted into the
  field.
* `{your-field-name:embed_url}` – A URL that is fit for embedding. E.g.
  `//www.youtube.com/embed/XXXXX` or `//player.vimeo.com/video/XXXXX`
* `{your-field-name:embed}` – An iframe embed. Use
  `{your-field-name:embed width="620"}` or
  `{your-field-name:embed width="500" height="380"}` to include a size.
* `{your-field-name:title}` – The title of the video.
* `{your-field-name:thumbnail}` – The thumbnail of the video. *Note: this is
  an 'http:' URL, because that is what both YouTube and Vimeo return.*
* `{your-field-name:type}` – returns either "youtube", "vimeo", or "unknown".
* `{your-field-name:valid}` – returns "yes" for YouTube and Vimeo URLs, and
  an empty string for unknown URLs.
* `{if your-field-name:valid}...{/if}` – determine if a valid YouTube or Vimeo
  embed has been entered.

## Pass-through parameters

For `{your-field-name:embed}` and `{your-field-name:embed_url}`, you can pass
parameters that will get added to the appropriate embedded URL. Prefix the
parameter with the video service, and the parameter will be added when the URL
is created.

For example, using the following tag,

```
{your-field-name:embed youtube:showinfo="0" vimeo:title="0" vimeo:byline="0"}
```

If the video is a YouTube video, it will use the URL
`//www.youtube.com/embed/XXXXX?rel=0&showinfo=0`. If the video is a Vimeo
video, it will use the URL `//player.vimeo.com/video/XXXXXX?title=0&byline=0`.

Note: YouTube videos default to `youtube:rel="0"`, but you may override that
with `youtube:rel=1`.

Consult the respective official documentation for [YouTube options][opts-yt]
and [Vimeo options][opts-vm].

# License

Video Link is distributed under the MIT license. See LICENSE.md for more
information.

Some icons by [Yusuke Kamiyamane](http://p.yusukekamiyamane.com/). Licensed
under a [Creative Commons Attribution 3.0
License](http://creativecommons.org/licenses/by/3.0/).

Loading gif by [ajaxload.info](http://www.ajaxload.info/). Free for use, but
hey, they deserve a shout-out anyway.

[opts-yt]: https://developers.google.com/youtube/player_parameters
[opts-vm]: http://developer.vimeo.com/player/embedding
