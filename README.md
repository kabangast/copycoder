# UI/UX Image Analysis with Gemini API

A web application that analyzes UI/UX designs using Google's Gemini API to provide comprehensive insights and feedback.

## Features

- 🖼️ Image Upload
  - Drag and drop interface
  - File type validation
  - Preview functionality
  - Progress indication

- 🤖 AI Analysis
  - Powered by Google's Gemini API (gemini-1.5-flash-latest)
  - Comprehensive UI/UX analysis including:
    - Layout analysis
    - Color palette identification
    - Typography evaluation
    - Interactive elements assessment
    - Accessibility considerations

- 💾 History Management
  - Stores recent analyses
  - Copy to clipboard functionality
  - Delete individual analyses
  - Clear all history option

## Tech Stack

- Frontend:
  - HTML5
  - CSS3 (Bootstrap)
  - JavaScript (Vanilla)
  - Font Awesome icons

- Backend:
  - PHP
  - Google Gemini API

## Setup

1. Prerequisites:
   - XAMPP or similar PHP development environment
   - PHP 8.0 or higher
   - Google Gemini API key

2. Installation:
   ```bash
   # Clone the repository
   git clone https://github.com/kabangast/copycoder.git

   # Move to your web server directory (e.g., XAMPP htdocs)
   cd /path/to/htdocs

   # Configure your API key
   # Edit gemini-1.5-flash-latest.php and replace YOUR_API_KEY with your actual Gemini API key
   ```

3. Configuration:
   - Set up your Gemini API key in `gemini-1.5-flash-latest.php`
   - Ensure proper file permissions
   - Configure your web server to serve PHP files

## Usage

1. Open the application in your web browser
2. Upload a UI/UX design image using drag & drop or file selection
3. Click "Generate Prompt" to analyze the image
4. View the detailed analysis in the results section
5. Copy or save the analysis as needed

## Analysis Components

The AI analyzes various aspects of UI/UX design:

1. Overall Design
   - Layout structure
   - Color schemes
   - Typography choices
   - Visual elements

2. Header Analysis
   - Navigation components
   - Branding elements
   - User interface controls

3. Main Content
   - Hero section evaluation
   - Content organization
   - Call-to-action placement

4. Interactive Elements
   - Button design
   - Form components
   - Modal implementations

5. User Experience
   - Information hierarchy
   - Navigation flow
   - Accessibility compliance

## API Response Format

The Gemini API returns analysis in a structured format:

```json
{
    "candidates": [{
        "content": {
            "parts": [{
                "text": "analysis_content"
            }],
            "role": "model"
        },
        "finishReason": "STOP"
    }],
    "modelVersion": "gemini-1.5-flash-latest"
}
```

## Security

- File type validation
- Server-side image processing
- Secure API key handling
- Error handling and validation

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgments

- Google Gemini API for powerful image analysis
- Bootstrap for responsive design
- Font Awesome for icons
