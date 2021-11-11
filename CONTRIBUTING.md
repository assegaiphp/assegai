# Contributing to Assegai

We would love for you to contribute to Assegai and help make it even better than it is today! As a contributor, here are the guidelines we would like you to follow:

- [Question or Problem?](#question-or-problem)
- [Issues and Bugs](#issues-and-bugs)
- [Feature Requests](#feature-requests)
- [Submission Guidelines](#submission-guidelines)
- [Development Setup](#development-setup)
- [Coding Rules](#coding-rules)
- [Commit Message Guidelines](#commit-message-guidelines)

## <a name="question-or-problem"></a> Got a Question or Problem?

**Do not open issues for general support questions as we want to keep GitHub issues for bug reports and feature requests.** If you do have general support quetions, then [Stack Overflow](https://stackoverflow.com/questions/tagged/assegaiphp) where the questions should be tagged with tag `assegaiphp`.

Stack Overflow is a much better place to ask questions since:

- Questions and answers stay available for public viewing so your question / answer might help someone else
- Stack Overflow's voting system assures taht the best answers are prominently visible.

To save yours and our time, we will systematically close all issues that are requests for general support and redirect people to Stack Overflow.

If you would like to thcat about the question in real-time, you can reach out via [our discord channel][discord]

## <a name="issues-and-bugs"></a> Found a Bug?

If you find a bug in the source ode, you can help us by
- [Submitting an issue](#submitting-an-issue)
- [Submitting a Pull Request](#submitting-a-pull-request) with a fix.

## <a name="feature-requests"></a> Missing a Feature?

You can _request_ a new feature by [submitting an issue](#submitting-an-issue) to our GitHub Repository. If you would like to _implement_a new feature, please submit an issue with a proposal for your work first, to be sure that we can use it.
Please consider what kind of change it is:

- For a **Major Feature**, first open an issue and outline your proposal so that it can be discussed. This will also allow us to better coordinate our efforts, prevent duplication of work and help you to craft the change so that it is successfully accepted into the project. For your issue name, please prefix your proposal with `[discussion]`, for example "[discussion]: your feature idea".

## <a name="submission-guidelines"></a> Submission Guidelines

### <a name="submitting-an-issue"></a> Submitting an Issue

Before you submit an issue, please search the issue tracker, maybe an issue for your problem already exists and the discussion might inform you of workarounds readily available.

We want to fix all the issues as oon as possible, but before fixing a bug we need to reproduce and confirm it. In order to reproduce bugs we will systematically ask you to provide a minimal reprodcution scenario using a repository or [Gist](https://gist.github.com/). Having a live, reproducible scenario gives us a wealth of important information without going back & forth to you with addtional questions like:

- version of Assegai used
- 3rd-party libraries and their versions
- and most importantly - a use-case that fails

Unfortunately, we are not able to investigate / fix bugs without a minimal reproduction, so if we don't hear back from you we are going to close an issue that doesn't have enough info to be reproduced.

You can file new issues by filling out our [new issue form](https://github.com/amasiye/assegai-php/issues)

### <a name="submitting-a-pull-request"></a> Submitting a Pull Request (PR)

Before you submit your Pull Request (PR) consider the following guidelines:

1. Search [GitHub](https://github.com/amasiye/assegai-php/pulls) for an open or closed PR that relates to your submission. You don't want to duplicate effort.
1. Fork the amasiye/assegai-php repo.
1. Make your changes in a new git branch:

   ```shell
   git checkout -b my-fix-branch master
   ```
1. Create your patch, **including appropriate test cases**.
1. Follow our [Coding Rules](#coding-rules).
1. Run the full Assegai test suite (see [Commonly used CLI commands](#common-cli-commands)) and ensure that all tests pass.
1. Commit your changes using a descriptive commit message that follows our [Commit Message Guidelines](#commit-message-guidelines). Adherence to these guidelines is necessary because release notes are automatically generated from these messages.

   ```shell
   git commit -a
   ```

   Note: the option `-a` command line option will automatically "add" and "rm" edited files.

1. Push your branch to GitHub:

   ```shell
   git push origin my-fix-branch
   ```

1. In GitHub, send a pull request to `assegai-php:master`.

- If we suggest changes then:

  - Make the required updates.
  - Re-run the Assegai test suites to ensure tests are still passing.
  - Rebase your branch and force push to your GitHub repository (this will update you Pull Request):

   ```shell
   git rebase master -i
   git push -f
   ```

That's it! Thank you for you contribution!

#### After your pull request is merged

After your pull request is merged, you can safely delete you branch and pull the changes from the main (upstream) repository:

- Delete the remote branch on GitHub either through the GitHub web UI or your local shell as follows:

   ```shell
   git push origin --delete my-fix-branch
   ```

- Check out the master branch:

   ```shell
   git checkout master -f
   ```

- Delete the local branch:

   ```shell
   git branch -D my-fix-branch
   ```

- Update your master with the lates upstream version:

   ```shell
   git pull --ff upstream master
   ```

## <a name="development-setup"></a> Development Setup

You will neeed PHP 8.0 or later.

1. After cloning the repo, run:

```bash
$ php composer.phar update
```

2. In order to prepare your environment run the `prepare.sh` shell script:

```bash
$ sh scripts/prepare.sh
```

That will compile fresh packages and afterward, move them all to `sample` directories.

### <a name="common-cli-commands"></a> Commonly used CLI commands

```bash
# Generate a feature
$ assegai generate feature <YOUR_FEATURE_NAME>

# Run the full unit tests suite
$ assegai test

# Run integration tests
# docker is required(!)
$ assegai test --type="integration"

# Update dependencies
$ assegai update

# Run linter
$ assegai run lint

# Run Autoload procedure
$ assegai run autoload
```

## <a name="coding-rules"></a> Coding Rules

To ensure consistency throughout the source code, keep these rules in mind as you are working:

- All features or bug fixes **must be tested** by one or more specs (unit-tests).
- We follow the [PSR-2: Coding Style Guide](https://www.php-fig.org/psr/psr-2/), but we use 2 spaces for indenting **instead of** 4 spaces, not tabs. An automated formatter is available (`assegai run format`).

## <a name="commit-message-guidelines"></a> Commit Message Guidelines

We have very precise rules over how our git commit messages can be formatted. This leads to **more readable messages** that are easy to follow when looking through the **project history**. But also, we use the git commit messages to **generate the Assegai change log**.

### Commit Message Format

Each commit message consists of a **header**, a **body** and a **footer**. The header has a special format that includes a **type**, a **scope** and a **subject**:

```
<type>(<scope>): <subject>
<BLANK LINE>
<body>
<BLANK LINE>
<footer>
```
The **header** is mandatory and the **scope** of the header is optional.

Any line of the commit message cannot be longer than 100 characters! This allows the message to be easier to read on GitHub as well as in various git tools.

The **footer** should contain a [closing reference to an issue](https://help.github.com/articles/closing-issues-via-commit-messages/) if any.

Samples: (even more [samples](https://github.com/amasiye/assegai-php/commits/master))

```
docs(changelog): update change log to beta.2
fix(core): update namespaces for core classes 
```

### Revert

If the commit reverts a previous commit, it should begin with `revert:`, followed by the header of the reverted commit. In the body it should say: `This reverts commit <hash>.`, where the has is the SHA of the commit being reverted.

### Type

Must be one of the following:

- **build**: Changes that affect the build system or external dependencies (example scopes: composer, assegai)
- **chore**: Updating tasks etc; no production code change
- **ci**: Changes to our CI configuration files and scripts (example scopes: )
- **docs**: Documentation only changes
- **feat**: A new feature
- **fix**: A bug fix
- **perf**: A code change that improves performance
- **refactor**: A code change that neither fixes a bug nor adds a feature
- **style**: Changes that do not affect the meaning of the code (white-space, formatting, missing semi-colons, etc)
- **test**: Adding missing tests or correcting existing tests
- **sample**: A change to the samples

### Scope

The scope should be the name of the composer package affected (as perceived by the person reading changelog generated from commit messages.)

The following is the list of supported scopes:

- **common**
- **core**
- **sample**
- **microservices**
- **testing**
- **websockets**

There are currently a few exceptions to the "use package name" rule:

- **packaging**: used for changes that change the composer package layout in all our packages, e.g public path changes, composer.json changes done to all packages, 
- **changelog**: used for updating the release notes in CHANGELOG.md
- **sample/#**: for the example apps directory, replacing # with the example app number
- none/empty string: useful for `style`, `test` and `refactor` changes that are done across all packages (e.g. `style: add missing semicolons`)

### Subject

The subject contains a succinct description of the change:

- use the imperative, present tense: "change" not "changed" nor "changes"
- don't capitalize the first letter
- no dot/full stop (.) at the end

### Body

Just as in the **subject**, use the imperative, present tense: "change" not "changed" nor "changes".
The body should include the motivation for the change and contrast this with previous behavior.

### Footer

The footer should contain any information about **Breaking Changes** and is also the place to reference GitHub issues that this commit **Closes**.

**Breaking Changes** should start with the words `BREAKING CHANGE:` with a space or two newlines. The rest of the commit message is then used for this.

A detailed explanation can be found in this [document][commit-message-format]

[commit-message-format]: https://docs.google.com/document/d/1QrDFcIiPjSLDn3EL15IJygNPiHORgU1_OOAqWjiDU5Y/edit#
[dev-doc]: https://github.com/amasiye/assegai-php/blob/master/docs/DEVELOPER.md
[github]: https://github.com/amasiye/assegai-php
[discord]: https://discord.gg/sR9hPAXR4z
[individual-cla]: http://code.google.com/legal/individual-cla-v1.0.html