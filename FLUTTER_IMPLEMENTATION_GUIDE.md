# Flutter Implementation Guide: Farming Resources (Videos & PDFs)

## Overview
This guide explains how to implement the Farming Resources feature in your Flutter app, supporting both videos and PDF documents with offline functionality.

## Features
- 📹 Video playback with offline support
- 📄 PDF viewing and downloading
- 🔍 Search and filtering by category, type, language
- 📱 Offline storage and sync
- 🌍 Multi-language support (English, Swahili, Luganda, etc.)
- 📍 Region-specific content

## API Endpoints

### Base URL
```
https://your-domain.com/api
```

### Available Endpoints
```
GET /farming-resources - List all resources with filters
GET /farming-resources/featured - Get featured resources
GET /farming-resources/categories - Get categories and types
GET /farming-resources/category/{category} - Get resources by category
GET /farming-resources/type/{type} - Get resources by type (video, pdf, document)
GET /farming-resources/search - Search resources
GET /farming-resources/{id} - Get specific resource
POST /farming-resources/{id}/download - Download resource
```

## Flutter Implementation

### 1. Dependencies
Add these to your `pubspec.yaml`:

```yaml
dependencies:
  flutter:
    sdk: flutter
  
  # HTTP requests
  http: ^1.1.0
  
  # Video player
  video_player: ^2.8.1
  chewie: ^1.7.4
  
  # PDF viewer
  syncfusion_flutter_pdfviewer: ^24.2.8
  
  # File download and storage
  path_provider: ^2.1.1
  permission_handler: ^11.0.1
  
  # State management
  provider: ^6.1.1
  
  # Local storage
  shared_preferences: ^2.2.2
  sqflite: ^2.3.0
  
  # UI components
  cached_network_image: ^3.3.0
  flutter_staggered_grid_view: ^0.7.0
```

### 2. Data Models

#### FarmingResource Model
```dart
class FarmingResource {
  final int id;
  final String title;
  final String? description;
  final String category;
  final String categoryName;
  final String? subcategory;
  final String type;
  final String typeName;
  final String typeIcon;
  final String? thumbnailUrl;
  final String? duration;
  final int? pageCount;
  final String fileSize;
  final String language;
  final String languageName;
  final bool isFeatured;
  final bool isOfflineAvailable;
  final int downloadCount;
  final int viewCount;
  final String? uploadedBy;
  final DateTime createdAt;
  final DateTime updatedAt;

  FarmingResource({
    required this.id,
    required this.title,
    this.description,
    required this.category,
    required this.categoryName,
    this.subcategory,
    required this.type,
    required this.typeName,
    required this.typeIcon,
    this.thumbnailUrl,
    this.duration,
    this.pageCount,
    required this.fileSize,
    required this.language,
    required this.languageName,
    required this.isFeatured,
    required this.isOfflineAvailable,
    required this.downloadCount,
    required this.viewCount,
    this.uploadedBy,
    required this.createdAt,
    required this.updatedAt,
  });

  factory FarmingResource.fromJson(Map<String, dynamic> json) {
    return FarmingResource(
      id: json['id'],
      title: json['title'],
      description: json['description'],
      category: json['category'],
      categoryName: json['category_name'],
      subcategory: json['subcategory'],
      type: json['type'],
      typeName: json['type_name'],
      typeIcon: json['type_icon'],
      thumbnailUrl: json['thumbnail_url'],
      duration: json['duration'],
      pageCount: json['page_count'],
      fileSize: json['file_size'],
      language: json['language'],
      languageName: json['language_name'],
      isFeatured: json['is_featured'],
      isOfflineAvailable: json['is_offline_available'],
      downloadCount: json['download_count'],
      viewCount: json['view_count'],
      uploadedBy: json['uploaded_by'],
      createdAt: DateTime.parse(json['created_at']),
      updatedAt: DateTime.parse(json['updated_at']),
    );
  }
}
```

### 3. API Service

```dart
class FarmingResourceService {
  static const String baseUrl = 'https://your-domain.com/api';
  
  // Get all resources with filters
  static Future<List<FarmingResource>> getResources({
    String? category,
    String? type,
    String? language,
    String? region,
    bool? featured,
    String? search,
  }) async {
    final queryParams = <String, String>{};
    if (category != null) queryParams['category'] = category;
    if (type != null) queryParams['type'] = type;
    if (language != null) queryParams['language'] = language;
    if (region != null) queryParams['region'] = region;
    if (featured != null) queryParams['featured'] = featured.toString();
    if (search != null) queryParams['search'] = search;

    final response = await http.get(
      Uri.parse('$baseUrl/farming-resources').replace(queryParameters: queryParams),
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return (data['data'] as List)
          .map((json) => FarmingResource.fromJson(json))
          .toList();
    } else {
      throw Exception('Failed to load resources');
    }
  }

  // Get featured resources
  static Future<List<FarmingResource>> getFeaturedResources() async {
    final response = await http.get(Uri.parse('$baseUrl/farming-resources/featured'));

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return (data['data'] as List)
          .map((json) => FarmingResource.fromJson(json))
          .toList();
    } else {
      throw Exception('Failed to load featured resources');
    }
  }

  // Get resource details
  static Future<FarmingResource> getResource(int id) async {
    final response = await http.get(Uri.parse('$baseUrl/farming-resources/$id'));

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return FarmingResource.fromJson(data['data']);
    } else {
      throw Exception('Failed to load resource');
    }
  }

  // Download resource
  static Future<Map<String, dynamic>> downloadResource(int id) async {
    final response = await http.post(Uri.parse('$baseUrl/farming-resources/$id/download'));

    if (response.statusCode == 200) {
      return json.decode(response.body)['data'];
    } else {
      throw Exception('Failed to download resource');
    }
  }
}
```

### 4. Offline Storage Service

```dart
class OfflineStorageService {
  static const String _dbName = 'farming_resources.db';
  static const String _tableName = 'resources';
  
  static Database? _database;

  static Future<Database> get database async {
    if (_database != null) return _database!;
    _database = await _initDatabase();
    return _database!;
  }

  static Future<Database> _initDatabase() async {
    final dbPath = await getDatabasesPath();
    final path = join(dbPath, _dbName);

    return await openDatabase(
      path,
      version: 1,
      onCreate: (db, version) async {
        await db.execute('''
          CREATE TABLE $_tableName(
            id INTEGER PRIMARY KEY,
            title TEXT,
            description TEXT,
            category TEXT,
            categoryName TEXT,
            subcategory TEXT,
            type TEXT,
            typeName TEXT,
            typeIcon TEXT,
            thumbnailUrl TEXT,
            duration TEXT,
            pageCount INTEGER,
            fileSize TEXT,
            language TEXT,
            languageName TEXT,
            isFeatured INTEGER,
            isOfflineAvailable INTEGER,
            downloadCount INTEGER,
            viewCount INTEGER,
            uploadedBy TEXT,
            createdAt TEXT,
            updatedAt TEXT,
            localFilePath TEXT,
            isDownloaded INTEGER DEFAULT 0
          )
        ''');
      },
    );
  }

  // Save resource to local database
  static Future<void> saveResource(FarmingResource resource, {String? localFilePath}) async {
    final db = await database;
    await db.insert(
      _tableName,
      {
        'id': resource.id,
        'title': resource.title,
        'description': resource.description,
        'category': resource.category,
        'categoryName': resource.categoryName,
        'subcategory': resource.subcategory,
        'type': resource.type,
        'typeName': resource.typeName,
        'typeIcon': resource.typeIcon,
        'thumbnailUrl': resource.thumbnailUrl,
        'duration': resource.duration,
        'pageCount': resource.pageCount,
        'fileSize': resource.fileSize,
        'language': resource.language,
        'languageName': resource.languageName,
        'isFeatured': resource.isFeatured ? 1 : 0,
        'isOfflineAvailable': resource.isOfflineAvailable ? 1 : 0,
        'downloadCount': resource.downloadCount,
        'viewCount': resource.viewCount,
        'uploadedBy': resource.uploadedBy,
        'createdAt': resource.createdAt.toIso8601String(),
        'updatedAt': resource.updatedAt.toIso8601String(),
        'localFilePath': localFilePath,
        'isDownloaded': localFilePath != null ? 1 : 0,
      },
      conflictAlgorithm: ConflictAlgorithm.replace,
    );
  }

  // Get all downloaded resources
  static Future<List<FarmingResource>> getDownloadedResources() async {
    final db = await database;
    final List<Map<String, dynamic>> maps = await db.query(
      _tableName,
      where: 'isDownloaded = ?',
      whereArgs: [1],
    );

    return List.generate(maps.length, (i) {
      return FarmingResource.fromJson(maps[i]);
    });
  }

  // Check if resource is downloaded
  static Future<bool> isResourceDownloaded(int id) async {
    final db = await database;
    final result = await db.query(
      _tableName,
      where: 'id = ? AND isDownloaded = ?',
      whereArgs: [id, 1],
    );
    return result.isNotEmpty;
  }

  // Get local file path
  static Future<String?> getLocalFilePath(int id) async {
    final db = await database;
    final result = await db.query(
      _tableName,
      columns: ['localFilePath'],
      where: 'id = ?',
      whereArgs: [id],
    );
    return result.isNotEmpty ? result.first['localFilePath'] as String? : null;
  }
}
```

### 5. Download Service

```dart
class DownloadService {
  static Future<String?> downloadFile(String url, String fileName) async {
    try {
      final response = await http.get(Uri.parse(url));
      
      if (response.statusCode == 200) {
        final directory = await getApplicationDocumentsDirectory();
        final filePath = '${directory.path}/farming_resources/$fileName';
        
        // Create directory if it doesn't exist
        final file = File(filePath);
        await file.parent.create(recursive: true);
        
        // Write file
        await file.writeAsBytes(response.bodyBytes);
        
        return filePath;
      }
    } catch (e) {
      print('Download error: $e');
    }
    return null;
  }

  static Future<void> downloadResource(FarmingResource resource) async {
    try {
      // Get download info from API
      final downloadInfo = await FarmingResourceService.downloadResource(resource.id);
      final fileUrl = downloadInfo['file_url'];
      
      // Generate filename
      final extension = resource.type == 'video' ? 'mp4' : 'pdf';
      final fileName = '${resource.id}_${resource.title.replaceAll(' ', '_')}.$extension';
      
      // Download file
      final localPath = await downloadFile(fileUrl, fileName);
      
      if (localPath != null) {
        // Save to local database
        await OfflineStorageService.saveResource(resource, localFilePath: localPath);
      }
    } catch (e) {
      print('Download resource error: $e');
      rethrow;
    }
  }
}
```

### 6. UI Implementation

#### Main Resources Screen
```dart
class FarmingResourcesScreen extends StatefulWidget {
  @override
  _FarmingResourcesScreenState createState() => _FarmingResourcesScreenState();
}

class _FarmingResourcesScreenState extends State<FarmingResourcesScreen> {
  List<FarmingResource> _resources = [];
  bool _isLoading = false;
  String? _selectedCategory;
  String? _selectedType;
  String? _selectedLanguage;

  @override
  void initState() {
    super.initState();
    _loadResources();
  }

  Future<void> _loadResources() async {
    setState(() => _isLoading = true);
    
    try {
      final resources = await FarmingResourceService.getResources(
        category: _selectedCategory,
        type: _selectedType,
        language: _selectedLanguage,
      );
      setState(() => _resources = resources);
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error loading resources: $e')),
      );
    } finally {
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Farming Resources'),
        actions: [
          IconButton(
            icon: Icon(Icons.search),
            onPressed: () => _showSearchDialog(),
          ),
          IconButton(
            icon: Icon(Icons.filter_list),
            onPressed: () => _showFilterDialog(),
          ),
        ],
      ),
      body: _isLoading
          ? Center(child: CircularProgressIndicator())
          : _resources.isEmpty
              ? Center(child: Text('No resources found'))
              : ListView.builder(
                  itemCount: _resources.length,
                  itemBuilder: (context, index) {
                    return ResourceCard(
                      resource: _resources[index],
                      onTap: () => _openResource(_resources[index]),
                    );
                  },
                ),
    );
  }

  void _openResource(FarmingResource resource) {
    if (resource.type == 'video') {
      Navigator.push(
        context,
        MaterialPageRoute(
          builder: (context) => VideoPlayerScreen(resource: resource),
        ),
      );
    } else if (resource.type == 'pdf') {
      Navigator.push(
        context,
        MaterialPageRoute(
          builder: (context) => PdfViewerScreen(resource: resource),
        ),
      );
    }
  }

  void _showSearchDialog() {
    // Implement search dialog
  }

  void _showFilterDialog() {
    // Implement filter dialog
  }
}
```

#### Resource Card Widget
```dart
class ResourceCard extends StatelessWidget {
  final FarmingResource resource;
  final VoidCallback onTap;

  const ResourceCard({
    Key? key,
    required this.resource,
    required this.onTap,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      child: InkWell(
        onTap: onTap,
        child: Padding(
          padding: EdgeInsets.all(16),
          child: Row(
            children: [
              // Thumbnail or Icon
              Container(
                width: 80,
                height: 80,
                decoration: BoxDecoration(
                  color: Colors.grey[200],
                  borderRadius: BorderRadius.circular(8),
                ),
                child: resource.thumbnailUrl != null
                    ? ClipRRect(
                        borderRadius: BorderRadius.circular(8),
                        child: CachedNetworkImage(
                          imageUrl: resource.thumbnailUrl!,
                          fit: BoxFit.cover,
                          placeholder: (context, url) => Center(
                            child: Text(resource.typeIcon, style: TextStyle(fontSize: 24)),
                          ),
                        ),
                      )
                    : Center(
                        child: Text(resource.typeIcon, style: TextStyle(fontSize: 24)),
                      ),
              ),
              
              SizedBox(width: 16),
              
              // Content
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      resource.title,
                      style: TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                      ),
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                    SizedBox(height: 4),
                    Text(
                      resource.categoryName,
                      style: TextStyle(
                        color: Colors.grey[600],
                        fontSize: 12,
                      ),
                    ),
                    SizedBox(height: 4),
                    Row(
                      children: [
                        Text(
                          resource.typeName,
                          style: TextStyle(
                            color: Colors.blue,
                            fontSize: 12,
                          ),
                        ),
                        SizedBox(width: 8),
                        if (resource.duration != null)
                          Text(
                            resource.duration!,
                            style: TextStyle(
                              color: Colors.grey[600],
                              fontSize: 12,
                            ),
                          ),
                        if (resource.pageCount != null) ...[
                          SizedBox(width: 8),
                          Text(
                            '${resource.pageCount} pages',
                            style: TextStyle(
                              color: Colors.grey[600],
                              fontSize: 12,
                            ),
                          ),
                        ],
                      ],
                    ),
                    SizedBox(height: 4),
                    Text(
                      resource.fileSize,
                      style: TextStyle(
                        color: Colors.grey[600],
                        fontSize: 12,
                      ),
                    ),
                  ],
                ),
              ),
              
              // Download button
              FutureBuilder<bool>(
                future: OfflineStorageService.isResourceDownloaded(resource.id),
                builder: (context, snapshot) {
                  final isDownloaded = snapshot.data ?? false;
                  return IconButton(
                    icon: Icon(
                      isDownloaded ? Icons.check_circle : Icons.download,
                      color: isDownloaded ? Colors.green : Colors.blue,
                    ),
                    onPressed: isDownloaded ? null : () => _downloadResource(context),
                  );
                },
              ),
            ],
          ),
        ),
      ),
    );
  }

  void _downloadResource(BuildContext context) async {
    try {
      await DownloadService.downloadResource(resource);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Resource downloaded successfully!')),
      );
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Download failed: $e')),
      );
    }
  }
}
```

### 7. Video Player Screen
```dart
class VideoPlayerScreen extends StatefulWidget {
  final FarmingResource resource;

  const VideoPlayerScreen({Key? key, required this.resource}) : super(key: key);

  @override
  _VideoPlayerScreenState createState() => _VideoPlayerScreenState();
}

class _VideoPlayerScreenState extends State<VideoPlayerScreen> {
  VideoPlayerController? _videoPlayerController;
  ChewieController? _chewieController;
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _initializePlayer();
  }

  Future<void> _initializePlayer() async {
    try {
      // Check if video is downloaded locally
      final localPath = await OfflineStorageService.getLocalFilePath(widget.resource.id);
      
      if (localPath != null) {
        _videoPlayerController = VideoPlayerController.file(File(localPath));
      } else {
        _videoPlayerController = VideoPlayerController.networkUrl(
          Uri.parse(widget.resource.fileUrl),
        );
      }

      await _videoPlayerController!.initialize();
      
      _chewieController = ChewieController(
        videoPlayerController: _videoPlayerController!,
        autoPlay: true,
        looping: false,
        aspectRatio: _videoPlayerController!.value.aspectRatio,
        allowFullScreen: true,
        allowMuting: true,
      );

      setState(() => _isLoading = false);
    } catch (e) {
      print('Video player error: $e');
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(widget.resource.title),
      ),
      body: _isLoading
          ? Center(child: CircularProgressIndicator())
          : _chewieController != null
              ? Column(
                  children: [
                    AspectRatio(
                      aspectRatio: _videoPlayerController!.value.aspectRatio,
                      child: Chewie(controller: _chewieController!),
                    ),
                    Padding(
                      padding: EdgeInsets.all(16),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            widget.resource.title,
                            style: TextStyle(
                              fontSize: 18,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          SizedBox(height: 8),
                          if (widget.resource.description != null)
                            Text(widget.resource.description!),
                        ],
                      ),
                    ),
                  ],
                )
              : Center(child: Text('Failed to load video')),
    );
  }

  @override
  void dispose() {
    _videoPlayerController?.dispose();
    _chewieController?.dispose();
    super.dispose();
  }
}
```

### 8. PDF Viewer Screen
```dart
class PdfViewerScreen extends StatefulWidget {
  final FarmingResource resource;

  const PdfViewerScreen({Key? key, required this.resource}) : super(key: key);

  @override
  _PdfViewerScreenState createState() => _PdfViewerScreenState();
}

class _PdfViewerScreenState extends State<PdfViewerScreen> {
  String? _pdfPath;
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadPdf();
  }

  Future<void> _loadPdf() async {
    try {
      // Check if PDF is downloaded locally
      final localPath = await OfflineStorageService.getLocalFilePath(widget.resource.id);
      
      if (localPath != null) {
        setState(() {
          _pdfPath = localPath;
          _isLoading = false;
        });
      } else {
        // Load from network
        setState(() {
          _pdfPath = widget.resource.fileUrl;
          _isLoading = false;
        });
      }
    } catch (e) {
      print('PDF loading error: $e');
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(widget.resource.title),
      ),
      body: _isLoading
          ? Center(child: CircularProgressIndicator())
          : _pdfPath != null
              ? SfPdfViewer.network(
                  _pdfPath!,
                  canShowPaginationDialog: true,
                  canShowScrollHead: true,
                  canShowScrollStatus: true,
                )
              : Center(child: Text('Failed to load PDF')),
    );
  }
}
```

## Usage Examples

### 1. Load Featured Resources
```dart
final featuredResources = await FarmingResourceService.getFeaturedResources();
```

### 2. Filter by Category
```dart
final cropResources = await FarmingResourceService.getResources(
  category: 'crop_management',
);
```

### 3. Filter by Type
```dart
final videos = await FarmingResourceService.getResources(type: 'video');
final pdfs = await FarmingResourceService.getResources(type: 'pdf');
```

### 4. Search Resources
```dart
final searchResults = await FarmingResourceService.getResources(
  search: 'pest control',
);
```

### 5. Download for Offline Use
```dart
await DownloadService.downloadResource(resource);
```

## Benefits for Your Project

1. **Educational Value**: Comprehensive learning materials for farmers
2. **Offline Access**: Works without internet connection
3. **Multi-format Support**: Videos and PDFs cater to different learning preferences
4. **Localized Content**: Region and language-specific resources
5. **User Engagement**: Keeps users coming back for new content
6. **Social Impact**: Directly improves farming practices
7. **Scalable**: Easy to add new content and categories

## Next Steps

1. Run the migration: `php artisan migrate`
2. Seed sample data: `php artisan db:seed --class=FarmingResourceSeeder`
3. Upload actual video and PDF files to your storage
4. Implement the Flutter UI following this guide
5. Test offline functionality
6. Add more content categories and resources

This implementation provides a robust foundation for your Farmer Training Center with excellent offline support and user experience! 