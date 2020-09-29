module.exports = {
    configureWebpack: {
        resolve: {
            alias: {
                'assets': '@/assets',
                'common': '@/common',
                'components': '@/components',
                'network': '@/network',
                'views': '@/views',
            }
        },


    },
    chainWebpack: config => {
        // 修复HMR
        config.resolve.symlinks(true);

    }
}