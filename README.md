# Video Link

Provide a fast, easy-to-use field type for adding links from YouTube and
Vimeo.

Video Link provides feedback to the user of the CMS immediately on whether the
URL they pasted into the field is a valid video, and provides the title and a
link to the video.

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
