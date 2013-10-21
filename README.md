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
party themes directory. (e.g. `/themes/third_party/navee/`)
3. Log into your ExpressionEngine control panel.
4. Open the Field Types tab.
5. Click "Install" by "Video List".

## Usage

Set "Video Link" as the Field Type.

## Tags

* `{your-field-name}` – The entire URL that the user typed or pasted into the
  field.
* `{your-field-name:embed_url}` – A URL that is fit for embedding. E.g.
  "//www.youtube.com/embed/XXXXX" or "//player.vimeo.com/video/XXXXX"
* `{your-field-name:embed}` – An iframe embed. Use
  `{your-file-name:embed width="620"}` or
  `{your-file-name:embed width="500" height="380"}` to include a size.

# License

Video Link is distributed under the MIT license. See LICENSE.md for more
information.

Some icons by [Yusuke Kamiyamane](http://p.yusukekamiyamane.com/). Licensed
under a [Creative Commons Attribution 3.0
License](http://creativecommons.org/licenses/by/3.0/).

Loading gif by [ajaxload.info](http://www.ajaxload.info/). Free for use, but
hey, they deserve a shout-out anyway.
