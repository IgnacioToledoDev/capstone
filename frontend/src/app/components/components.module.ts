import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { IonicModule } from '@ionic/angular';
import { MenuComponent } from './menu/menu.component';
import { MenucliComponent } from './menucli/menucli.component';

@NgModule({
  declarations: [MenuComponent,MenucliComponent], // Declaras el componente
  imports: [
    CommonModule,
    IonicModule
  ],
  exports: [
    MenuComponent,MenucliComponent // Exportas el componente
  ],
})
export class ComponentsModule { }
