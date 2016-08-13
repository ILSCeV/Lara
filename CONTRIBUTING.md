## Language
To allow contributors who don't know German well enough to participate in the development process the following rules apply:

- All comments within the code **must** be written in **English**.
- All variable and function names **must** be in **English**.
- Issues, pull requests and bug reports *should* ideally be written in **English**, but **German** is acceptable. Important parts might be translated to English later.
- Answers to issues / pull requests can be either in **English** or in **German** - whatever is more convenient for the participants. 

## Bug reports 
Bug reports may be either sent directly at [Maxim](mailto:maxim.drachinskiy@bc-studentenclub.de) via E-Mail or in the form of a pull request. 

Please describe the problem in as many details as possible, yet at least including:
- where (URL) and when (time) was the problem seen?
- what went wrong?
- what did you expect instead?
- did it happen only once or is the problem still there?

To encourage active collaboration **we strongly encourage pull requests, not just bug reports**!

After the pull request or an email with the bug report is submitted, an issue will be opened by the current project supervisor (right now that's [Maxim](https://github.com/4D44H/)).

## I want to submit some code!
Awesome! We try to get our code as close as possible to the following Coding Style Guides:
- [PSR-2 Code Standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) for PHP
- [Google JavaScript Style Guide](https://google.github.io/styleguide/javascriptguide.xml) for JS
- [Google HTML/CSS Style Guide](https://google.github.io/styleguide/htmlcssguide.xml) for HTML/CSS
- [Google Material Design Guidelines](https://material.google.com/) for UX/UI elements

**Please check that standards before submitting your changes - this will make everyone's life easier**! But if your code style isn't perfect, don't worry - we are not very strict, and functionality is more important than bureaucracy ;)

Afterwards [create a pull request](https://github.com/ILSCeV/lara-vedst/compare) and **describe the changes you made** in a couple of sentences. 

## Which branch?
*(Right now this structure is not implemented yet -> planned for 08.08.2016-15.08.2016)*

- **master** has the latest stable production-ready state. 

Generally, you should **NOT** create pull requests to **master** - please use development branches instead (listed below):

- **develop** has latest delivered development changes for the next release. 
- **feature branches** are used to develop new features for the upcoming or a distant future release.
- **release branches** support preparation of a new production release and allow for last-minute minor bug fixes and Co.
- **hotfix branches** are for when a critical bug in a production version must be resolved immediately - the essence is that work of team members (on the develop branch) can continue, while another person is preparing a quick production fix.

**tl;dr**:

<img src="http://nvie.com/img/git-model@2x.png" width="500">

**Further details:** ["A successful git branching model" by Vincent Driessen](http://nvie.com/posts/a-successful-git-branching-model/)

## Versioning
Given a version number MAJOR.MINOR.PATCH (e.g. 2.0.0), increment the:
- MAJOR version when you make incompatible API changes,
- MINOR version when you add functionality in a backwards-compatible manner, and
- PATCH version when you make backwards-compatible bug fixes.
Additional labels for pre-release and build metadata are available as extensions to the MAJOR.MINOR.PATCH format.

**Further details**: [Semantic versioning v2.0.0 by Tom Preston-Werner](http://semver.org/)

If you are unsure if your feature qualifies as a major or minor or where to put it, please contact [Maxim](https://github.com/4D44H).