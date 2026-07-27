<?php

namespace App\Enums;

enum AttachmentType: string
{
    case Pdf = 'pdf';
    case Docx = 'docx';
    case Txt = 'txt';
    case Image = 'image';
    case Mp3 = 'mp3';
    case Wav = 'wav';
    case Mp4 = 'mp4';
    case BackingTrack = 'backing_track';
    case Lyrics = 'lyrics';
    case ChordSheet = 'chord_sheet';
    case StageNotes = 'stage_notes';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Pdf => 'PDF',
            self::Docx => 'DOCX',
            self::Txt => 'Text',
            self::Image => 'Image',
            self::Mp3 => 'MP3 Audio',
            self::Wav => 'WAV Audio',
            self::Mp4 => 'MP4 Video',
            self::BackingTrack => 'Backing Track',
            self::Lyrics => 'Lyrics',
            self::ChordSheet => 'Chord Sheet',
            self::StageNotes => 'Stage Notes',
            self::Other => 'Other',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Pdf => 'file-text',
            self::Docx => 'file-text',
            self::Txt => 'file-text',
            self::Image => 'image',
            self::Mp3 => 'music',
            self::Wav => 'music',
            self::Mp4 => 'video',
            self::BackingTrack => 'music',
            self::Lyrics => 'file-text',
            self::ChordSheet => 'file-text',
            self::StageNotes => 'file-text',
            self::Other => 'file',
        };
    }
}
