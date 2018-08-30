
#SkinnyLink - _Put your links on a diet_ 

This project allows you to shrink your links. It is a SF 3.4 based application. Feel free to test.

#Requirements

* VM Memory available >= 2048
* PHP 7.1
* MySQL 5.7
* [composer - Dependency Manager for PHP](https://getcomposer.org/download/) 
* [ext-pdo](http://php.net/manual/en/pdo.installation.php)
* [ext-pdo-mysql](http://php.net/manual/en/ref.pdo-mysql.php)
* [ext-xml](http://php.net/manual/en/dom.setup.php)

#Install

```bash
$ git clone https://github.com/filipefernandes007/skinnylink
```

#Bonus

Vagrant file if you prefer. Just run: 

```bash
$ vagrant up
```

#Troubleshooting with Vagrant

During our tests i found the bellow message. Maybe you can also find it.

"_Vagrant was unable to mount VirtualBox shared folders. This is usually because the filesystem "vboxsf" is not available. This filesystem is made available via the VirtualBox Guest Additions and kernel module. Please verify that these guest additions are properly installed in the guest. This is not a bug in Vagrant and is usually caused by a faulty Vagrant box. For context, the command attempted was: mount -t vboxsf -o dmode=777,fmode=666,uid=1000,gid=1000 var_www /var/www The error output from the command was: No such device_"

Don't worry, just run this command:

```bash
$ vagrant reload
```

If you see the same message again, go to this [link](https://stackoverflow.com/questions/43492322/vagrant-was-unable-to-mount-virtualbox-shared-folders). There are several and good approaches to follow.

#Run application

Now start the application (it does not start automatically, but if you want, uncomment the `` php bin/console server:run 192.168.33.89:8000 `` command in your Vagrant file to do so next time you 'reload' Vagrant):  

```bash
$ vagrant ssh
$ cd var/www
$ composer run-app # OR php bin/console server:run 192.168.33.89:8000 
```

#Unit tests

You can run tests with bash command ``` composer test ```

Apply ``` php bin/console cache:clear ``` if you get this message after ``` composer test ``` : 

"_Remaining deprecation notices (1)
   1x: The "Sensio\Bundle\FrameworkExtraBundle\Configuration\Route" annotation is deprecated since version 5.2. Use "Symfony\Component\Routing\Annotation\Route" instead._
"

Enjoy!