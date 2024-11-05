import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { IonicModule } from '@ionic/angular';
import { MenuComponent } from './menu/menu.component';
import { MenucliComponent } from './menucli/menucli.component';
import { OcrComponent } from './ocr/ocr.component';

@NgModule({
  declarations: [MenuComponent,MenucliComponent,OcrComponent], // Declaras el componente
  imports: [
    CommonModule,
    IonicModule
  ],
  exports: [
    MenuComponent,MenucliComponent,OcrComponent // Exportas el componente
  ],
})
export class ComponentsModule { }
