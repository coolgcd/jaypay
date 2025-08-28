 @extends('layouts.front')


 @section('title', 'legal documents')


 @section('content')


 <!-- Legal Documents -->
 <section id="legal" class="section-padding">
     <div class="container">
         <div class="section-title animate-on-scroll">
             <h2>Legal Documents</h2>
             <p>Verified and certified legal documents for transparency and compliance</p>
         </div>
         <div class="legal-documents-grid">
             <div class="legal-document-card animate-on-scroll">
                 <div class="legal-document-header">
                     <h4><i class="fas fa-file-alt"></i> SPICE + Part B Approval Letter</h4>
                 </div>
                 <iframe class="pdf-viewer" src="/storage/legal/SPICE%20%2B%20Part%20B_Approval%20Letter_AA4872620.pdf" type="application/pdf"></iframe>
                 <div class="pdf-fallback">
                     <a href="/storage/legal/SPICE%20%2B%20Part%20B_Approval%20Letter_AA4872620.pdf" target="_blank">
                         <i class="fas fa-eye"></i> View PDF
                     </a>
                 </div>

             </div>

             <div class="legal-document-card animate-on-scroll">
                 <div class="legal-document-header">
                     <h4><i class="fas fa-id-card"></i> PAN Card</h4>
                 </div>
                 <iframe class="pdf-viewer" src="/storage/legal/jay%20ho%20pan.pdf" type="application/pdf"></iframe>
                 <div class="pdf-fallback">
                     <a href="/storage/legal/jay%20ho%20pan.pdf" target="_blank">
                         <i class="fas fa-eye"></i> View PDF
                     </a>
                 </div>
             </div>

             <div class="legal-document-card animate-on-scroll">
                 <div class="legal-document-header">
                     <h4><i class="fas fa-gavel"></i> Memorandum of Association (EMoA)</h4>
                 </div>
                 <iframe class="pdf-viewer" src="/storage/legal/emoa-final.pdf" type="application/pdf"></iframe>
                 <div class="pdf-fallback">
                     <a href="/storage/legal/emoa-final.pdf" target="_blank">
                         <i class="fas fa-eye"></i> View PDF
                     </a>
                 </div>
             </div>

             <div class="legal-document-card animate-on-scroll">
                 <div class="legal-document-header">
                     <h4><i class="fas fa-balance-scale"></i> Articles of Association (EAoA)</h4>
                 </div>
                 <iframe class="pdf-viewer" src="/storage/legal/eaoa-final.pdf" type="application/pdf"></iframe>
                 <div class="pdf-fallback">
                     <a href="/storage/legal/eaoa-final.pdf" target="_blank">
                         <i class="fas fa-eye"></i> View PDF
                     </a>
                 </div>
             </div>

             <div class="legal-document-card animate-on-scroll">
                 <div class="legal-document-header">
                     <h4><i class="fas fa-certificate"></i> Company Incorporation Certificate</h4>
                 </div>
                 <iframe class="pdf-viewer" src="/storage/legal/AA091024021993B_RC05102024.pdf" type="application/pdf"></iframe>
                 <div class="pdf-fallback">
                     <a href="/storage/legal/AA091024021993B_RC05102024.pdf" target="_blank">
                         <i class="fas fa-eye"></i> View PDF
                     </a>
                 </div>
             </div>

             <!-- <div class="legal-document-card animate-on-scroll">
                    <div class="legal-document-header">
                        <h4><i class="fas fa-shield-alt"></i> Privacy Policy & Terms</h4>
                    </div>
                    <div style="padding: 40px; text-align: center; background: #f8f9fa; height: 400px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                        <i class="fas fa-file-contract" style="font-size: 3rem; color: var(--primary-blue); margin-bottom: 20px;"></i>
                        <h5 style="color: var(--black); margin-bottom: 15px;">Legal Compliance Document</h5>
                        <p style="color: var(--dark-gray); margin-bottom: 20px;">Our comprehensive privacy policy and terms of service ensuring full legal compliance.</p>
                        <a href="#" class="btn-custom" style="padding: 10px 30px; font-size: 1rem;">View Full Document</a>
                    </div>
                </div> -->
         </div>
     </div>
 </section>
 @endsection