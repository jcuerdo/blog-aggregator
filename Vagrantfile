# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

    config.vm.box = "scotch/box"
    config.vm.network "private_network", ip: "192.168.50.10"
    config.vm.hostname = "blogaggregator"
    config.vm.synced_folder ".", "/vagrant", id: "application", :nfs => true

    # if Vagrant.has_plugin?("vagrant-cachier")
    #     config.cache.scope = :machine

    #     config.cache.synced_folder_opts = {
    #         type: :nfs,
    #         mount_options: ['rw', 'vers=3', 'tcp', 'nolock']
    #     }

    #     config.cache.enable :generic, {
    #         "cache"  => { cache_dir: "/var/www/app/cache" },
    #         "logs"   => { cache_dir: "/var/www/app/logs" },
    #         "vendor" => { cache_dir: "/var/www/vendor" },
    #     }
    # end

    config.vm.provision "shell" do |s|
      s.path = "provision/configure-web.sh"
    end
end
