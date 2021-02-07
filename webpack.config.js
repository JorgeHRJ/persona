const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

const configs = ['cms', 'site'];
let exportConfigs = [];

configs.forEach((config) => {
  Encore
    .setOutputPath(`public/build/${config}/`)
    .setPublicPath(`/build/${config}`)
    .addEntry(config, `./assets/${config}/app.js`)
    .splitEntryChunks()
    .enableSassLoader()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning()
    .configureBabelPresetEnv((config) => {
      config.useBuiltIns = 'usage';
      config.corejs = 3;
    })
    .copyFiles({
      from: `./assets/${config}/images`,
      to: `images/${config}/[path][name].[ext]`,
    });

  const exportConfig = Encore.getWebpackConfig();
  exportConfig.name = config;

  exportConfigs.push(exportConfig);

  Encore.reset();
});

module.exports = exportConfigs;
