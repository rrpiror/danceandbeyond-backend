#!/bin/bash

# Flush Spatie Media Library Script
# This script clears both database records and physical files

echo "🧹 Flushing Spatie Media Library..."

# Check if we're in the correct directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Please run this script from the Laravel project root."
    exit 1
fi

# Clear database records
echo "📊 Clearing media records from database..."
php artisan tinker --execute="Spatie\MediaLibrary\MediaCollections\Models\Media::truncate();"

if [ $? -eq 0 ]; then
    echo "✅ Database records cleared successfully"
else
    echo "❌ Failed to clear database records"
    exit 1
fi

# Clear physical files
echo "📁 Clearing physical files from storage/app/public..."
rm -rf storage/app/public/*

if [ $? -eq 0 ]; then
    echo "✅ Physical files cleared successfully"
else
    echo "❌ Failed to clear physical files"
    exit 1
fi

# Recreate the .gitignore file in storage/app/public if it doesn't exist
if [ ! -f "storage/app/public/.gitignore" ]; then
    echo "📝 Recreating .gitignore in storage/app/public..."
    echo "*" > storage/app/public/.gitignore
    echo "!.gitignore" >> storage/app/public/.gitignore
fi

# Run the seeder
echo "🌱 Running migrations and database seeder..."
php artisan migrate:fresh --seed

if [ $? -eq 0 ]; then
    echo "✅ Migrations and database seeder completed successfully"
else
    echo "❌ Failed to run migrations and database seeder"
    exit 1
fi

echo "🎉 Media flush completed successfully!"
echo "📈 Summary:"
echo "   - Database records: CLEARED"
echo "   - Physical files: CLEARED"
echo "   - Migrations: RUN"
echo "   - Database seeder: RUN"
echo "   - Storage structure: PRESERVED" 