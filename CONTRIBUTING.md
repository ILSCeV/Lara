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
- ["How to Write a Git Commit Message" (by Chris Beams)](http://chris.beams.io/posts/git-commit/) for Git commit messages

**Please check that standards before submitting your changes - this will make everyone's life easier**! 

Afterwards [create a pull request](https://github.com/ILSCeV/lara-vedst/compare) and **describe the changes you made** in a couple of sentences. 

## Which branch?
Release branches:
- **master** represents current state in Lara on the production server and should only be committed to if Lara is also updated.
- **berta** represents current state in Berta (Beta-version of Lara) and should only be committed to if Berta is also updated.
- **release/x.x.x** branches are for preparation of new beta/production releases and allow for last-minute minor bug fixes and Co. 

Generally, you should **NOT** create pull requests to **master**, **berta** or **release/x.x.x** - please use development branches instead (listed below):

- **develop** has latest delivered development changes for the next release. 
- **feature branches** are used to develop new features for the upcoming or a distant future release.
- **hotfix branches** are for when a critical bug in a production version must be resolved immediately - the essence is that work of team members (on the develop branch) can continue, while another person is preparing a quick production fix.

**tl;dr**:

<img src="http://nvie.com/img/git-model@2x.png" width="500">

**Further details:** ["A successful git branching model" by Vincent Driessen](http://nvie.com/posts/a-successful-git-branching-model/)

## Git HowTo

### Git config
```
git config --global merge.ff only
git config --global fetch.prune true
git config --global pull.rebase true
```

### Updating your branch
Assuming you are writing a new feature **feature/supercool** and you want to integrate the latest changes from **develop**:
 1. ```git checkout feature/supercool```
 2. ```git fetch```
 3. ```git rebase origin origin/development```
 4. ```git push origin/feature/supercool --force```

### Merging a bugfix or a feature
Assuming you wrote a new feature **feature/supercool** and you want to integrate it into **develop**:
 1. ```git checkout feature/supercool```
 2. ```git fetch```
 3. ```git rebase origin origin/development```
 4. ```git checkout development```
 5. ```git rebase origin/development```
 6. ```git merge --ff-only feature/supercool```
 7. ```git merge --squash feature/supercool```

**Step 6** will take your commits and place them on top of the head of **develop**.
Alternatively, you can use **step 7** - this will squash your commits to a single one. 


## Versioning
Given a version number MAJOR.MINOR.PATCH (e.g. 2.0.0), increment the:
- MAJOR version when you make incompatible API changes,
- MINOR version when you add functionality in a backwards-compatible manner, and
- PATCH version when you make backwards-compatible bug fixes.

Additional labels for pre-release and build metadata are available as extensions to the MAJOR.MINOR.PATCH format.

**Further details**: [Semantic versioning v2.0.0 by Tom Preston-Werner](http://semver.org/)

If you are unsure if your feature qualifies as a major or minor or where to put it, please contact [Maxim](https://github.com/4D44H).