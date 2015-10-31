#TODO after pull ngRatOn directory
**Bash instruction to properly install environment and dependency for execute client project**
```bash
cd ngRatOn
npm i -g yo gulp bower tsd
npm install
bower install
tsd install
```

- ``` gulp ``` to build an optimized version of your application in folder dist
- ```gulp serve``` to start BrowserSync server on your source files with live reload
- ```gulp serve:dist``` to start BrowserSync server on your optimized application without live reload
- ```gulp test``` to run your unit tests with Karma
- ```gulp test:auto``` to run your unit tests with Karma in watch mode
- ```gulp protractor``` to launch your e2e tests with Protractor
- ```gulp protractor:dist``` to launch your e2e tests with Protractor on the dist files





