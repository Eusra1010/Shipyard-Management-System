@extends('layouts.admin')
@section('title', 'Edit News')
@section('page-title', 'Edit News')
@section('breadcrumb', 'NavalForge / News / Edit')

@section('content')

<div style="max-width:680px;">

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:22px;">
        <a href="{{ route('admin.news.index') }}"
           style="display:inline-flex;align-items:center;gap:6px;font-size:12px;color:#64748b;">
            <i class="fas fa-arrow-left" style="font-size:10px;"></i> Back
        </a>
        <h1 style="font-size:18px;font-weight:800;color:#0f172a;">Edit Announcement</h1>
    </div>

    @if($errors->any())
    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:18px;">
        @foreach($errors->all() as $err)
        <div style="font-size:13px;color:#dc2626;"><i class="fas fa-exclamation-circle" style="margin-right:5px;"></i>{{ $err }}</div>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('admin.news.update', $item->id) }}">
        @csrf
        @method('PATCH')

        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:24px;display:flex;flex-direction:column;gap:18px;">

            <div>
                <label style="display:block;font-size:12px;font-weight:700;color:#475569;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em;">
                    Title <span style="color:#dc2626;">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title', $item->title) }}"
                       style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:8px;font-size:14px;color:#0f172a;outline:none;"
                       onfocus="this.style.borderColor='#1d4ed8'" onblur="this.style.borderColor='#e2e8f0'">
            </div>

            <div>
                <label style="display:block;font-size:12px;font-weight:700;color:#475569;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em;">
                    Description <span style="color:#dc2626;">*</span>
                </label>
                <textarea name="description" rows="5"
                          style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:8px;font-size:14px;color:#0f172a;resize:vertical;outline:none;font-family:inherit;"
                          onfocus="this.style.borderColor='#1d4ed8'" onblur="this.style.borderColor='#e2e8f0'">{{ old('description', $item->description) }}</textarea>
            </div>

            <div>
                <label style="display:block;font-size:12px;font-weight:700;color:#475569;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em;">
                    Publish Date <span style="color:#dc2626;">*</span>
                </label>
                <input type="date" name="published_at" value="{{ old('published_at', $item->published_at) }}"
                       style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:8px;font-size:14px;color:#0f172a;outline:none;"
                       onfocus="this.style.borderColor='#1d4ed8'" onblur="this.style.borderColor='#e2e8f0'">
            </div>

            <div style="border-top:1px solid #f1f5f9;padding-top:18px;">
                <div style="font-size:12px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:14px;">
                    Optional Attachments
                </div>

                <div style="display:flex;flex-direction:column;gap:14px;">
                    <div>
                        <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:5px;">
                            Thumbnail image path
                        </label>
                        <input type="text" name="image_path" value="{{ old('image_path', $item->image_path) }}"
                               placeholder="/images/news/example.jpg"
                               style="width:100%;padding:9px 14px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;color:#0f172a;outline:none;"
                               onfocus="this.style.borderColor='#1d4ed8'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <div>
                        <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:5px;">
                            External link (URL)
                        </label>
                        <input type="url" name="link" value="{{ old('link', $item->link) }}"
                               placeholder="https://..."
                               style="width:100%;padding:9px 14px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;color:#0f172a;outline:none;"
                               onfocus="this.style.borderColor='#1d4ed8'" onblur="this.style.borderColor='#e2e8f0'">
                        <div style="font-size:11px;color:#94a3b8;margin-top:4px;">This becomes the "Details" link on the public News &amp; Events page.</div>
                    </div>

                    <div>
                        <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:5px;">
                            PDF path
                        </label>
                        <input type="text" name="pdf_path" value="{{ old('pdf_path', $item->pdf_path) }}"
                               placeholder="/files/press-release.pdf"
                               style="width:100%;padding:9px 14px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;color:#0f172a;outline:none;"
                               onfocus="this.style.borderColor='#1d4ed8'" onblur="this.style.borderColor='#e2e8f0'">
                        <div style="font-size:11px;color:#94a3b8;margin-top:4px;">Put PDF in <code>public/files/</code> and enter its path here.</div>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:10px;padding-top:4px;">
                <button type="submit"
                        style="padding:10px 24px;background:#1d4ed8;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;">
                    Save changes
                </button>
                <a href="{{ route('admin.news.index') }}"
                   style="padding:10px 20px;border:1px solid #e2e8f0;border-radius:8px;font-size:14px;font-weight:600;color:#64748b;">
                    Cancel
                </a>
            </div>

        </div>
    </form>

</div>

@endsection
