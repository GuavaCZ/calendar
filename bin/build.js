import esbuild from 'esbuild'

const isDev = process.argv.includes('--dev')

async function compile(options) {
    const context = await esbuild.context(options)

    if (isDev) {
        await context.watch()
    } else {
        await context.rebuild()
        await context.dispose()
    }
}

const defaultOptions = {
    define: {
        'process.env.NODE_ENV': isDev ? `'development'` : `'production'`,
    },
    bundle: true,
    mainFields: ['module', 'main'],
    platform: 'neutral',
    // `platform: 'neutral'` applies no export conditions of its own, so packages that branch on
    // `browser` vs `default` fall through to their server build. @event-calendar/core imports
    // `svelte`, whose exports map resolves to the SSR entry without this — and that entry's
    // `mount()` throws `lifecycle_function_unavailable` at runtime, with no build-time error.
    conditions: ['browser'],
    sourcemap: isDev ? 'inline' : false,
    sourcesContent: isDev,
    treeShaking: true,
    target: ['es2020'],
    minify: !isDev,
    plugins: [{
        name: 'watchPlugin',
        setup: function (build) {
            build.onStart(() => {
                console.log(`Build started at ${new Date(Date.now()).toLocaleTimeString()}: ${build.initialOptions.outfile}`)
            })

            build.onEnd((result) => {
                if (result.errors.length > 0) {
                    console.log(`Build failed at ${new Date(Date.now()).toLocaleTimeString()}: ${build.initialOptions.outfile}`, result.errors)
                } else {
                    console.log(`Build finished at ${new Date(Date.now()).toLocaleTimeString()}: ${build.initialOptions.outfile}`)
                }
            })
        }
    }],
}

compile({
    ...defaultOptions,
    entryPoints: ['./resources/js/calendar.js'],
    outfile: './dist/js/calendar.js',
})

compile({
    ...defaultOptions,
    entryPoints: ['./resources/js/calendar-context-menu.js'],
    outfile: './dist/js/calendar-context-menu.js',
})

compile({
    ...defaultOptions,
    entryPoints: ['./resources/js/calendar-event.js'],
    outfile: './dist/js/calendar-event.js',
})

// The library's own stylesheet, vendored out of node_modules so it can be served from the
// package instead of a CDN. This is not the user-facing `resources/css/theme.css`, which
// stays a Tailwind source file that consumers import into their own Filament theme.
compile({
    define: defaultOptions.define,
    plugins: defaultOptions.plugins,
    bundle: true,
    minify: !isDev,
    sourcemap: isDev ? 'inline' : false,
    entryPoints: ['./resources/css/calendar.css'],
    outfile: './dist/css/calendar.css',
})

// compile({
//     ...defaultOptions,
//     entryPoints: ['./resources/js/calendar-context-menu.js'],
//     outfile: './dist/js/calendar-context-menu.js',
// })
//
// compile({
//     ...defaultOptions,
//     entryPoints: ['./resources/js/event.js'],
//     outfile: './dist/js/event.js',
// })
