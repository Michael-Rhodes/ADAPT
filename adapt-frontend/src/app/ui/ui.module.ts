import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { UiComponent } from './ui.component';

import {RouterModule} from '@angular/router';

@NgModule({
    imports: [
      CommonModule,
      RouterModule,
    ],
    declarations: [
      UiComponent
    ],
})
export class UiModule { }
