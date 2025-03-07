import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'
import PreviewField from './components/PreviewField'

Nova.booting((app, store) => {
  app.component('index-test-field', IndexField)
  app.component('detail-test-field', DetailField)
  app.component('form-test-field', FormField)
  // app.component('preview-test-field', PreviewField)
})
