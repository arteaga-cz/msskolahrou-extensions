/**
 * Build distribution ZIP
 *
 * Replicates the Grunt compress task for distribution.
 */

import { createWriteStream, existsSync, unlinkSync } from 'fs';
import { resolve, dirname } from 'path';
import { fileURLToPath } from 'url';
import archiver from 'archiver';

const __dirname = dirname(fileURLToPath(import.meta.url));
const rootDir = resolve(__dirname, '..');

process.chdir(rootDir);

const outputPath = resolve(rootDir, 'msskolahrou-extensions.zip');

// Remove existing ZIP if present
if (existsSync(outputPath)) {
	unlinkSync(outputPath);
}

const output = createWriteStream(outputPath);
const archive = archiver('zip', { zlib: { level: 9 } });

output.on('close', () => {
	const size = (archive.pointer() / 1024).toFixed(2);
	console.log(`Created: msskolahrou-extensions.zip (${size} KB)`);
});

archive.on('error', (err) => {
	throw err;
});

archive.pipe(output);

// Assets directory (exclude backups and source maps)
archive.glob('assets/**/*', {
	ignore: ['assets/**/*.backup.css', 'assets/**/*.map']
});

// Other directories
archive.directory('includes/', 'includes');
archive.directory('languages/', 'languages');
archive.directory('views/', 'views');
archive.directory('vendor/', 'vendor');

// Root files
archive.glob('*.php');
archive.glob('*.css');
archive.glob('*.txt');
archive.glob('*.md', { ignore: ['CLAUDE.md'] });

archive.finalize();
