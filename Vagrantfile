Vagrant.configure("2") do |config|
    config.vm.box = "ubuntu/bionic64"

    config.vm.network "private_network", ip: "192.168.25.100"

    config.vm.synced_folder ".", "/vagrant", disabled: true
    config.vm.synced_folder "./vagrant/ssh", "/root/.ssh/", 
        :owner=> 'root', :group=>'root', mount_options: ['dmode=600,fmode=600']

    config.vm.network "forwarded_port", guest: 22, host: 7685

    config.vm.provider "virtualbox" do |v|
        v.memory = 4096
        v.cpus = 4
    end
end