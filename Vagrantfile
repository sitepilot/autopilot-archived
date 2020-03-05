Vagrant.configure("2") do |config|
    config.vm.box = "ubuntu/bionic64"

    config.vm.network "private_network", ip: "192.168.25.100"

    config.vm.synced_folder ".", "/vagrant", disabled: true
    config.vm.synced_folder "./", "/home/vagrant/autopilot", type: "nfs", 
        mount_options: ['rw', 'vers=3', 'tcp', 'nolock']

    config.vm.provider "virtualbox" do |v|
        v.memory = 4096
        v.cpus = 4
    end

    config.vm.network "forwarded_port", guest: 80, host: 8080
    config.vm.network "forwarded_port", guest: 443, host: 8443

    config.vm.provision "ansible" do |ansible|
        ansible.playbook = "./ansible/local.yml"
        ansible.extra_vars = { 
            host: "default", 
            connection: "ssh", 
            ansible_python_interpreter:"/usr/bin/python3" 
        }
    end
end